<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockAlert;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    // The per-product low-stock alert cooldown is cache-backed; clear it so
    // tests don't leak state via colliding auto-increment product IDs.
    Cache::flush();
});

function createLowStockTestProduct(int $stock, int $threshold): Product
{
    return Product::create([
        'category_id' => Category::create(['name' => 'Test Category '.uniqid()])->id,
        'sku' => 'SKU-'.uniqid(),
        'name' => 'Test Product',
        'price' => 100,
        'stock' => $stock,
        'low_stock_threshold' => $threshold,
        'is_active' => true,
    ]);
}

test('a low stock notification is sent when remaining stock equals the threshold', function () {
    Notification::fake();

    $product = createLowStockTestProduct(stock: 10, threshold: 7);
    $cashier = User::factory()->create();

    app(TransactionService::class)->checkout([
        'items' => [
            ['product_id' => $product->id, 'quantity' => 3],
        ],
        'discount_amount' => 0,
        'tax_amount' => 0,
        'payment_method' => 'cash',
        'amount_tendered' => 1000,
        'cashier_id' => $cashier->id,
    ]);

    expect($product->fresh()->stock)->toBe(7);

    Notification::assertSentTo($cashier, LowStockAlert::class);
});

test('no low stock notification is sent when remaining stock is still above the threshold', function () {
    Notification::fake();

    $product = createLowStockTestProduct(stock: 10, threshold: 5);
    $cashier = User::factory()->create();

    app(TransactionService::class)->checkout([
        'items' => [
            ['product_id' => $product->id, 'quantity' => 3],
        ],
        'discount_amount' => 0,
        'tax_amount' => 0,
        'payment_method' => 'cash',
        'amount_tendered' => 1000,
        'cashier_id' => $cashier->id,
    ]);

    expect($product->fresh()->stock)->toBe(7);

    Notification::assertNotSentTo($cashier, LowStockAlert::class);
});

test('a low stock notification is sent when stock drops past the threshold in one sale', function () {
    Notification::fake();

    $product = createLowStockTestProduct(stock: 10, threshold: 5);
    $cashier = User::factory()->create();

    app(TransactionService::class)->checkout([
        'items' => [
            ['product_id' => $product->id, 'quantity' => 8],
        ],
        'discount_amount' => 0,
        'tax_amount' => 0,
        'payment_method' => 'cash',
        'amount_tendered' => 1000,
        'cashier_id' => $cashier->id,
    ]);

    expect($product->fresh()->stock)->toBe(2);

    Notification::assertSentTo($cashier, LowStockAlert::class);
});

test('a low stock notification is sent again when stock was already at or below the threshold before the sale', function () {
    Notification::fake();

    $product = createLowStockTestProduct(stock: 5, threshold: 5);
    $cashier = User::factory()->create();

    app(TransactionService::class)->checkout([
        'items' => [
            ['product_id' => $product->id, 'quantity' => 1],
        ],
        'discount_amount' => 0,
        'tax_amount' => 0,
        'payment_method' => 'cash',
        'amount_tendered' => 1000,
        'cashier_id' => $cashier->id,
    ]);

    expect($product->fresh()->stock)->toBe(4);

    Notification::assertSentTo($cashier, LowStockAlert::class);
});

test('a second low-stock-triggering checkout for the same product within the cooldown window does not send another notification', function () {
    Notification::fake();

    $product = createLowStockTestProduct(stock: 10, threshold: 8);
    $cashier = User::factory()->create();

    $checkout = fn () => app(TransactionService::class)->checkout([
        'items' => [
            ['product_id' => $product->id, 'quantity' => 1],
        ],
        'discount_amount' => 0,
        'tax_amount' => 0,
        'payment_method' => 'cash',
        'amount_tendered' => 1000,
        'cashier_id' => $cashier->id,
    ]);

    $checkout();
    $checkout();

    Notification::assertSentToTimes($cashier, LowStockAlert::class, 1);
});

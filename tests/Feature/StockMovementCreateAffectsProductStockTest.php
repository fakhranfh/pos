<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\StockMovementService;
use Illuminate\Validation\ValidationException;

function createStockTestProduct(int $stock = 10): Product
{
    return Product::create([
        'category_id' => Category::create(['name' => 'Test Category '.uniqid()])->id,
        'sku' => 'SKU-'.uniqid(),
        'name' => 'Test Product',
        'price' => 100,
        'stock' => $stock,
        'low_stock_threshold' => 1,
        'is_active' => true,
    ]);
}

test('creating a stock_in movement increases product stock', function () {
    $product = createStockTestProduct(10);
    $user = User::factory()->create();

    app(StockMovementService::class)->create([
        'product_id' => $product->id,
        'type' => 'stock_in',
        'quantity_change' => 5,
        'reason' => null,
        'user_id' => $user->id,
    ]);

    expect($product->fresh()->stock)->toBe(15);
});

test('creating a sale movement decreases product stock', function () {
    $product = createStockTestProduct(10);
    $user = User::factory()->create();

    app(StockMovementService::class)->create([
        'product_id' => $product->id,
        'type' => 'sale',
        'quantity_change' => -4,
        'reason' => null,
        'user_id' => $user->id,
    ]);

    expect($product->fresh()->stock)->toBe(6);
});

test('creating a return movement increases product stock', function () {
    $product = createStockTestProduct(10);
    $user = User::factory()->create();

    app(StockMovementService::class)->create([
        'product_id' => $product->id,
        'type' => 'return',
        'quantity_change' => 3,
        'reason' => null,
        'user_id' => $user->id,
    ]);

    expect($product->fresh()->stock)->toBe(13);
});

test('creating an adjustment movement can decrease product stock', function () {
    $product = createStockTestProduct(10);
    $user = User::factory()->create();

    app(StockMovementService::class)->create([
        'product_id' => $product->id,
        'type' => 'adjustment',
        'quantity_change' => -2,
        'reason' => 'Damaged goods',
        'user_id' => $user->id,
    ]);

    expect($product->fresh()->stock)->toBe(8);
});

test('a movement that would push stock negative is rejected', function () {
    $product = createStockTestProduct(3);
    $user = User::factory()->create();

    expect(fn () => app(StockMovementService::class)->create([
        'product_id' => $product->id,
        'type' => 'sale',
        'quantity_change' => -5,
        'reason' => null,
        'user_id' => $user->id,
    ]))->toThrow(ValidationException::class);

    expect($product->fresh()->stock)->toBe(3);
});

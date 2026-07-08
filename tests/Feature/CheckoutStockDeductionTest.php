<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\TransactionService;

test('checkout decrements product stock exactly once', function () {
    $product = Product::create([
        'category_id' => Category::create(['name' => 'Test Category '.uniqid()])->id,
        'sku' => 'SKU-'.uniqid(),
        'name' => 'Test Product',
        'price' => 100,
        'stock' => 10,
        'low_stock_threshold' => 1,
        'is_active' => true,
    ]);
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
    expect($product->stockMovements()->count())->toBe(1);
});

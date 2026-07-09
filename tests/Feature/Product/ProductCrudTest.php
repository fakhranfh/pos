<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\TransactionItem;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('admin can create a product with name, sku, price, category, and initial stock', function () {
    $category = Category::factory()->create();

    $response = $this->post(route('products.store'), [
        'category_id' => $category->id,
        'sku' => 'SKU-001',
        'name' => 'Test Product',
        'price' => 15000,
        'stock' => 20,
        'low_stock_threshold' => 5,
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('products', [
        'sku' => 'SKU-001',
        'name' => 'Test Product',
        'category_id' => $category->id,
        'stock' => 20,
    ]);
});

test('duplicate sku is rejected with a validation error', function () {
    $category = Category::factory()->create();
    Product::factory()->create(['sku' => 'DUP-SKU', 'category_id' => $category->id]);

    $response = $this->post(route('products.store'), [
        'category_id' => $category->id,
        'sku' => 'DUP-SKU',
        'name' => 'Another Product',
        'price' => 5000,
        'stock' => 10,
        'low_stock_threshold' => 2,
        'is_active' => true,
    ]);

    $response->assertSessionHasErrors('sku');
    $this->assertDatabaseCount('products', 1);
});

test('category must exist before a product can reference it', function () {
    $response = $this->post(route('products.store'), [
        'category_id' => 9999,
        'sku' => 'SKU-002',
        'name' => 'No Category Product',
        'price' => 5000,
        'stock' => 10,
        'low_stock_threshold' => 2,
        'is_active' => true,
    ]);

    $response->assertSessionHasErrors('category_id');
});

test('admin can update a product', function () {
    $product = Product::factory()->create(['name' => 'Old Name']);

    $response = $this->put(route('products.update', $product), [
        'category_id' => $product->category_id,
        'sku' => $product->sku,
        'name' => 'New Name',
        'price' => $product->price,
        'stock' => $product->stock,
        'low_stock_threshold' => $product->low_stock_threshold,
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New Name']);
});

test('deleting a product referenced in past transactions is soft-deleted to preserve transaction history', function () {
    $product = Product::factory()->create();
    $transactionItem = TransactionItem::factory()->create(['product_id' => $product->id]);

    $response = $this->delete(route('products.destroy', $product));

    $response->assertRedirect();
    $this->assertSoftDeleted('products', ['id' => $product->id]);
    $this->assertDatabaseHas('transaction_items', ['id' => $transactionItem->id, 'product_id' => $product->id]);
});

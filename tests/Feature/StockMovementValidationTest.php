<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

function createValidationTestProduct(): Product
{
    return Product::create([
        'category_id' => Category::create(['name' => 'Test Category '.uniqid()])->id,
        'sku' => 'SKU-'.uniqid(),
        'name' => 'Test Product',
        'price' => 100,
        'stock' => 10,
        'low_stock_threshold' => 1,
        'is_active' => true,
    ]);
}

test('reason is required when creating an adjustment movement', function () {
    $product = createValidationTestProduct();

    $response = $this->post(route('stock-movements.store'), [
        'product_id' => $product->id,
        'type' => 'adjustment',
        'quantity_change' => -2,
        'reason' => '',
    ]);

    $response->assertSessionHasErrors('reason');
});

test('reason is not required when creating a stock-in movement', function () {
    $product = createValidationTestProduct();

    $response = $this->post(route('stock-movements.store'), [
        'product_id' => $product->id,
        'type' => 'stock_in',
        'quantity_change' => 2,
        'reason' => '',
    ]);

    $response->assertSessionDoesntHaveErrors('reason');
});

test('sale-type movements cannot be submitted manually', function () {
    $product = createValidationTestProduct();

    $response = $this->post(route('stock-movements.store'), [
        'product_id' => $product->id,
        'type' => 'sale',
        'quantity_change' => -2,
        'reason' => '',
    ]);

    $response->assertSessionHasErrors('type');
});

test('the authenticated user is recorded as the actor on creation', function () {
    $product = createValidationTestProduct();
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('stock-movements.store'), [
        'product_id' => $product->id,
        'type' => 'stock_in',
        'quantity_change' => 5,
        'reason' => '',
    ]);

    $response->assertSessionDoesntHaveErrors();
    $this->assertDatabaseHas('stock_movements', [
        'product_id' => $product->id,
        'user_id' => $user->id,
    ]);
});

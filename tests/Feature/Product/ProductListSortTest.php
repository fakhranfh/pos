<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

function createTestProduct(string $name): Product
{
    return Product::create([
        'category_id' => Category::create(['name' => 'Test Category '.uniqid()])->id,
        'sku' => 'SKU-'.uniqid(),
        'name' => $name,
        'price' => 100,
        'stock' => 10,
        'low_stock_threshold' => 1,
        'is_active' => true,
    ]);
}

test('product list sorts by an allowed column', function () {
    createTestProduct('Zebra');
    createTestProduct('Apple');

    $response = $this->getJson(route('products.list', ['sort' => 'name', 'direction' => 'asc']));

    $response->assertSuccessful();
    expect($response->json('data.*.name'))->toBe(['Apple', 'Zebra']);
});

test('product list ignores a non-whitelisted sort column', function () {
    createTestProduct('Zebra');
    createTestProduct('Apple');

    $response = $this->getJson(route('products.list', ['sort' => 'password', 'direction' => 'asc']));

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(2);
});

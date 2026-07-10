<?php

use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

test('low stock page lists products at or below their threshold', function () {
    $lowProduct = Product::factory()->create(['stock' => 2, 'low_stock_threshold' => 5]);
    $okProduct = Product::factory()->create(['stock' => 10, 'low_stock_threshold' => 5]);

    $response = $this->get(route('products.low-stock'));

    $response->assertOk();
    $response->assertSee($lowProduct->name);
    $response->assertDontSee($okProduct->name);
});

test('low stock page shows an empty state when no products are low', function () {
    Product::factory()->create(['stock' => 10, 'low_stock_threshold' => 5]);

    $response = $this->get(route('products.low-stock'));

    $response->assertOk();
    $response->assertSee('No products are low on stock.');
});

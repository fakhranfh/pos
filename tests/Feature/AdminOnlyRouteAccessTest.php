<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;

test('a cashier is forbidden from managing categories', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('categories.index'))->assertForbidden();
    $this->post(route('categories.store'), ['name' => 'Drinks'])->assertForbidden();
});

test('a cashier is forbidden from managing products', function () {
    $this->actingAs(User::factory()->create());
    $product = Product::factory()->create();

    $this->get(route('products.index'))->assertForbidden();
    $this->get(route('products.show', $product))->assertForbidden();
});

test('a cashier is forbidden from creating stock movements', function () {
    $this->actingAs(User::factory()->create());
    $product = Product::factory()->create();

    $this->post(route('stock-movements.store'), [
        'product_id' => $product->id,
        'type' => 'stock_in',
        'quantity_change' => 5,
    ])->assertForbidden();
});

test('a cashier is forbidden from viewing the transaction ledger and reports', function () {
    $this->actingAs(User::factory()->create());
    $transaction = Transaction::factory()->create();

    $this->get(route('transactions.index'))->assertForbidden();
    $this->get(route('transactions.show', $transaction))->assertForbidden();
    $this->get(route('reports.sales'))->assertForbidden();
});

test('an admin can access categories, products, stock movements, transactions, and reports', function () {
    $this->actingAs(User::factory()->admin()->create());
    $category = Category::factory()->create();
    $product = Product::factory()->create();
    $transaction = Transaction::factory()->create();

    $this->get(route('categories.index'))->assertSuccessful();
    $this->get(route('categories.show', $category))->assertSuccessful();
    $this->get(route('products.index'))->assertSuccessful();
    $this->get(route('transactions.index'))->assertSuccessful();
    $this->get(route('transactions.show', $transaction))->assertSuccessful();
    $this->get(route('reports.sales'))->assertSuccessful();
});

test('a manager can access categories, products, stock movements, transactions, and reports', function () {
    $this->actingAs(User::factory()->manager()->create());
    $category = Category::factory()->create();
    $product = Product::factory()->create();
    $transaction = Transaction::factory()->create();

    $this->get(route('categories.index'))->assertSuccessful();
    $this->get(route('categories.show', $category))->assertSuccessful();
    $this->get(route('products.index'))->assertSuccessful();
    $this->get(route('transactions.index'))->assertSuccessful();
    $this->get(route('transactions.show', $transaction))->assertSuccessful();
    $this->get(route('reports.sales'))->assertSuccessful();
});

test('a manager is forbidden from user management, unlike an admin', function () {
    $this->actingAs(User::factory()->manager()->create());
    $target = User::factory()->create();

    $this->get(route('users.index'))->assertForbidden();
    $this->get(route('users.edit', $target))->assertForbidden();
    $this->put(route('users.update', $target), ['role' => 'admin'])->assertForbidden();
});

test('admins, managers, and cashiers can all use the checkout screen', function () {
    $this->actingAs(User::factory()->create());
    $this->get(route('checkout.index'))->assertSuccessful();

    $this->actingAs(User::factory()->manager()->create());
    $this->get(route('checkout.index'))->assertSuccessful();

    $this->actingAs(User::factory()->admin()->create());
    $this->get(route('checkout.index'))->assertSuccessful();
});

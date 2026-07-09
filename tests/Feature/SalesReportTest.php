<?php

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('sales report totals completed transactions and excludes voided ones within the date range', function () {
    Transaction::factory()->create(['status' => 'completed', 'total' => 100000, 'created_at' => now()]);
    Transaction::factory()->create(['status' => 'completed', 'total' => 50000, 'created_at' => now()]);
    Transaction::factory()->create(['status' => 'voided', 'total' => 999999, 'created_at' => now()]);
    Transaction::factory()->create(['status' => 'completed', 'total' => 999999, 'created_at' => now()->subDays(10)]);

    $response = $this->get(route('reports.sales', [
        'date_from' => now()->toDateString(),
        'date_to' => now()->toDateString(),
    ]));

    $response->assertOk();
    $response->assertViewHas('totalSales', 150000.0);
    $response->assertViewHas('totalTransactions', 2);
});

test('sales report totals endpoint returns json for ajax filtering', function () {
    Transaction::factory()->create(['status' => 'completed', 'total' => 100000, 'created_at' => now()]);
    Transaction::factory()->create(['status' => 'voided', 'total' => 999999, 'created_at' => now()]);

    $response = $this->getJson(route('reports.sales.totals', [
        'date_from' => now()->toDateString(),
        'date_to' => now()->toDateString(),
    ]));

    $response->assertOk();
    $response->assertJson([
        'totalSales' => 100000.0,
        'totalTransactions' => 1,
    ]);
});

test('sales report products table lists best-selling products by revenue', function () {
    $transaction = Transaction::factory()->create(['status' => 'completed', 'created_at' => now()]);

    $topProduct = Product::factory()->create(['name' => 'Best Seller']);
    TransactionItem::factory()->create([
        'transaction_id' => $transaction->id,
        'product_id' => $topProduct->id,
        'product_name' => $topProduct->name,
        'quantity' => 10,
        'line_total' => 1000000,
    ]);

    $otherProduct = Product::factory()->create(['name' => 'Other Product']);
    TransactionItem::factory()->create([
        'transaction_id' => $transaction->id,
        'product_id' => $otherProduct->id,
        'product_name' => $otherProduct->name,
        'quantity' => 1,
        'line_total' => 1000,
    ]);

    $response = $this->getJson(route('reports.sales.products', [
        'date_from' => now()->toDateString(),
        'date_to' => now()->toDateString(),
    ]));

    $response->assertOk();
    $response->assertJsonPath('data.0.name', 'Best Seller');
    $response->assertJsonCount(2, 'data');
});

test('sales report products table excludes items outside the selected date range', function () {
    $oldTransaction = Transaction::factory()->create(['status' => 'completed', 'created_at' => now()->subDays(30)]);
    $product = Product::factory()->create(['name' => 'Old Product']);
    TransactionItem::factory()->create([
        'transaction_id' => $oldTransaction->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
    ]);

    $response = $this->getJson(route('reports.sales.products', [
        'date_from' => now()->toDateString(),
        'date_to' => now()->toDateString(),
    ]));

    $response->assertOk();
    $response->assertJsonCount(0, 'data');
});

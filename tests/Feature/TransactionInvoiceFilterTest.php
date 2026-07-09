<?php

use App\Models\Transaction;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('transaction list filters invoice number by partial match', function () {
    Transaction::factory()->create(['invoice_number' => 'INV-20260709-0001']);
    Transaction::factory()->create(['invoice_number' => 'INV-20260709-0002']);
    Transaction::factory()->create(['invoice_number' => 'INV-20260710-0001']);

    $response = $this->getJson(route('transactions.list', ['invoice_number' => '20260709']));

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(2);
});

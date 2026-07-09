<?php

use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Laravel\Dusk\Browser;

beforeEach(function () {
    Browser::$storeScreenshotsAt = base_path('docs/dusk/images/pos');

    $this->seed(DatabaseSeeder::class);
});

/**
 * @group screenshots
 */
test('capture dashboard screenshot', function () {
    $user = User::where('email', 'andi.admin@possystem.test')->firstOrFail();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/dashboard')
            ->pause(1000)
            ->screenshot('dashboard');
    });
});

test('capture checkout page screenshot', function () {
    $user = User::where('email', 'budi.kasir@possystem.test')->firstOrFail();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/checkout')
            ->pause(1000)
            ->screenshot('checkout-page');
    });
});

test('capture category index screenshot', function () {
    $user = User::where('email', 'andi.admin@possystem.test')->firstOrFail();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/categories')
            ->pause(1000)
            ->screenshot('category-index');
    });
});

test('capture transaction index screenshot', function () {
    $user = User::where('email', 'andi.admin@possystem.test')->firstOrFail();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/transactions')
            ->pause(1000)
            ->screenshot('transaction-index');
    });
});

test('capture transaction show screenshot', function () {
    $user = User::where('email', 'andi.admin@possystem.test')->firstOrFail();
    $transaction = Transaction::latest('created_at')->firstOrFail();

    $this->browse(function (Browser $browser) use ($user, $transaction) {
        $browser->loginAs($user)
            ->visit("/transactions/{$transaction->id}")
            ->pause(1000)
            ->screenshot('transaction-show');
    });
});

test('capture sales report screenshot', function () {
    $user = User::where('email', 'andi.admin@possystem.test')->firstOrFail();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/reports/sales?date_from='.now()->subDays(5)->toDateString().'&date_to='.now()->toDateString())
            ->pause(1000)
            ->screenshot('sales-report');
    });
});

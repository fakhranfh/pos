<?php

use App\Models\User;

test('cashier sees the cashier dashboard view', function () {
    $cashier = User::factory()->create();

    $this->actingAs($cashier)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard-cashier');
});

test('admin sees the full dashboard view', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard');
});

test('manager sees the full dashboard view', function () {
    $manager = User::factory()->manager()->create();

    $this->actingAs($manager)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard');
});

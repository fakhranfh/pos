<?php

use App\Enums\UserRole;
use App\Models\User;

test('a cashier is forbidden from the user management screens', function () {
    $this->actingAs(User::factory()->create());
    $target = User::factory()->create();

    $this->get(route('users.index'))->assertForbidden();
    $this->get(route('users.edit', $target))->assertForbidden();
    $this->put(route('users.update', $target), ['role' => 'admin'])->assertForbidden();
});

test('an admin can list users and see their roles', function () {
    $this->actingAs(User::factory()->admin()->create());
    User::factory()->create(['name' => 'Cashier One']);

    $response = $this->getJson(route('users.list'));

    $response->assertSuccessful();
    expect(collect($response->json('data'))->pluck('name'))->toContain('Cashier One');
});

test('an admin can promote a cashier to admin', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $target = User::factory()->create();

    $response = $this->put(route('users.update', $target), ['role' => 'admin']);

    $response->assertRedirect(route('users.index'));
    expect($target->fresh()->role)->toBe(UserRole::Admin);
});

test('an admin can promote a cashier to manager', function () {
    $this->actingAs(User::factory()->admin()->create());
    $target = User::factory()->create();

    $response = $this->put(route('users.update', $target), ['role' => 'manager']);

    $response->assertRedirect(route('users.index'));
    expect($target->fresh()->role)->toBe(UserRole::Manager);
});

test('an admin can demote another admin to cashier when enough admins remain', function () {
    $this->actingAs(User::factory()->admin()->create());
    User::factory()->admin()->create();
    $target = User::factory()->admin()->create();

    $response = $this->put(route('users.update', $target), ['role' => 'cashier']);

    $response->assertRedirect(route('users.index'));
    expect($target->fresh()->role)->toBe(UserRole::Cashier);
});

test('an admin cannot demote another admin if it would drop below the minimum admin count', function () {
    User::query()->where('role', UserRole::Admin)->delete();
    $this->actingAs(User::factory()->admin()->create());
    $target = User::factory()->admin()->create();

    $response = $this->put(route('users.update', $target), ['role' => 'cashier']);

    $response->assertRedirect(route('users.edit', $target));
    $response->assertSessionHasErrors('role');
    expect($target->fresh()->role)->toBe(UserRole::Admin);
});

test('an admin cannot demote themselves, to avoid locking everyone out', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);

    $response = $this->put(route('users.update', $admin), ['role' => 'cashier']);

    $response->assertRedirect(route('users.edit', $admin));
    $response->assertSessionHasErrors('role');
    expect($admin->fresh()->role)->toBe(UserRole::Admin);
});

test('role must be a known enum value', function () {
    $this->actingAs(User::factory()->admin()->create());
    $target = User::factory()->create();

    $this->put(route('users.update', $target), ['role' => 'superadmin'])
        ->assertSessionHasErrors('role');
});

<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Http;

// Prevent actual HTTP requests to the Pwned Passwords API during tests
beforeEach(function () {
    Http::fake([
        'api.pwnedpasswords.com/*' => Http::response('', 200),
        'ip-api.com/*' => Http::response(['status' => 'success', 'timezone' => 'Asia/Jakarta'], 200),
    ]);
});

test('registration page can be rendered', function () {
    $this->get('/register')->assertSuccessful();
});

test('user can register with valid data', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
    ]);

    $response->assertRedirect('/dashboard');

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
});

test('a self-registered account is always the cashier role, regardless of any submitted role', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
    ]);

    expect(User::where('email', 'john@example.com')->first()->role)->toBe(UserRole::Cashier);
});

test('registration ignores a submitted manager or admin role and forces cashier', function (string $role) {
    $this->post('/register', [
        'name' => 'Mona Doe',
        'email' => 'mona@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
        'role' => $role,
    ])->assertRedirect('/dashboard');

    expect(User::where('email', 'mona@example.com')->first()->role)->toBe(UserRole::Cashier);
})->with(['manager', 'admin']);

test('registering stores the timezone resolved from the request IP', function () {
    $this->call('POST', '/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
        'role' => 'cashier',
    ], [], [], ['REMOTE_ADDR' => '8.8.8.8'])->assertRedirect('/dashboard');

    $this->call('GET', '/dashboard', server: ['REMOTE_ADDR' => '8.8.8.8']);

    expect(User::where('email', 'john@example.com')->first()->timezone)->toBe('Asia/Jakarta');
});

test('registering from a private/loopback IP still resolves a timezone', function () {
    // Simulates local development, where the client IP is always private
    // and cannot be geolocated directly.
    $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
        'role' => 'cashier',
    ])->assertRedirect('/dashboard');

    $this->get('/dashboard');

    expect(User::where('email', 'jane@example.com')->first()->timezone)->toBe('Asia/Jakarta');
});

test('registration fails when name is empty', function () {
    $this->post('/register', [
        'name' => '',
        'email' => 'john@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
    ])->assertSessionHasErrors('name');
});

test('registration fails when email is empty', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => '',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
    ])->assertSessionHasErrors('email');
});

test('registration fails when email format is invalid', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'not-an-email',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
    ])->assertSessionHasErrors('email');
});

test('registration fails when email is already taken', function () {
    User::factory()->create(['email' => 'john@example.com']);

    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
    ])->assertSessionHasErrors('email');
});

test('registration fails when password is less than 8 characters', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Sh0rt!',
        'password_confirmation' => 'Sh0rt!',
    ])->assertSessionHasErrors('password');
});

test('registration fails when password has no mixed case', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123#!',
        'password_confirmation' => 'password123#!',
    ])->assertSessionHasErrors('password');
});

test('registration fails when password has no numbers', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password#!Secure',
        'password_confirmation' => 'Password#!Secure',
    ])->assertSessionHasErrors('password');
});

test('registration fails when password has no symbols', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123Secure',
        'password_confirmation' => 'Password123Secure',
    ])->assertSessionHasErrors('password');
});

test('registration fails when password confirmation does not match', function () {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Different!Pass123#Secure',
    ])->assertSessionHasErrors('password');
});

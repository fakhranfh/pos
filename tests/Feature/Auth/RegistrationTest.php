<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Prevent actual HTTP requests to the Pwned Passwords API during tests.
// FEATURE_REGISTRATION_ENABLED is forced on for the whole test suite (see
// phpunit.xml) since these tests exercise the /register flow; production
// defaults it off (see Finding 1 in the pentest report).
beforeEach(function () {
    Http::fake([
        'api.pwnedpasswords.com/*' => Http::response('', 200),
        'ip-api.com/*' => Http::response(['status' => 'success', 'timezone' => 'Asia/Jakarta'], 200),
    ]);
});

test('registration routes are not registered when the feature flag is off', function () {
    putenv('FEATURE_REGISTRATION_ENABLED=false');
    $_ENV['FEATURE_REGISTRATION_ENABLED'] = 'false';
    $_SERVER['FEATURE_REGISTRATION_ENABLED'] = 'false';
    $this->refreshApplication();

    expect(Route::has('register'))->toBeFalse();
    $this->get('/register')->assertNotFound();

    putenv('FEATURE_REGISTRATION_ENABLED=true');
    $_ENV['FEATURE_REGISTRATION_ENABLED'] = 'true';
    $_SERVER['FEATURE_REGISTRATION_ENABLED'] = 'true';
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

test('registering stores the timezone resolved from the request IP', function () {
    $this->call('POST', '/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Secret!Pass123#Secure',
        'password_confirmation' => 'Secret!Pass123#Secure',
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

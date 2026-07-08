<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('', function () {
        return view('auth.login');
    })->name('login');
});

Route::view('/', 'landing-page');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('edit-profile');
    Route::post('/edit-profile', [ProfileController::class, 'update']);
    Route::get('/edit-profile/verify-email', [ProfileController::class, 'verifyEmailChange'])->name('profile.verify-email-change');

    Route::view('/change-password', 'change-password')->name('change-password');

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    });

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

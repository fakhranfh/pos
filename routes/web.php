<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
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

    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('notifications/data/list', [NotificationController::class, 'list'])->name('notifications.list');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('checkout/products', [CheckoutController::class, 'search'])->name('checkout.products');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('checkout/{transaction}/receipt', [CheckoutController::class, 'receipt'])->name('checkout.receipt');

    // Inventory management, ledger review, and reporting are open to admins
    // and managers; cashiers are limited to the checkout flow above.
    Route::middleware('manager')->group(function () {
        Route::get('categories/data/list', [CategoryController::class, 'list'])->name('categories.list');
        Route::resource('categories', CategoryController::class);

        Route::get('products/data/list', [ProductController::class, 'list'])->name('products.list');
        Route::get('products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
        Route::resource('products', ProductController::class);

        Route::get('stock-movements/data/list', [StockMovementController::class, 'list'])->name('stock-movements.list');
        Route::resource('stock-movements', StockMovementController::class)->only(['index', 'show', 'create', 'store']);

        Route::get('transactions/data/list', [TransactionController::class, 'list'])->name('transactions.list');
        Route::resource('transactions', TransactionController::class)->only(['index', 'show']);

        Route::get('reports/sales/data/list', [ReportController::class, 'salesProducts'])->name('reports.sales.products');
        Route::get('reports/sales/data/totals', [ReportController::class, 'salesTotals'])->name('reports.sales.totals');
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    });

    // RBAC/user management is admin-only; managers do not get this.
    Route::middleware('admin')->group(function () {
        Route::get('users/data/list', [UserController::class, 'list'])->name('users.list');
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    });
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

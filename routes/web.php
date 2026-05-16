<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protect Routes semua user login
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    //hanya admin yang bisa akses
    Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('transactions', TransactionController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::get('reports', [DashboardController::class, 'index'])->name('reports.index');
        Route::get('reports/pdf', [DashboardController::class, 'pdf'])->name('reports.pdf');
    });

    // admin dan kasir bisa akses
    Route::middleware([RoleMiddleware::class . ':admin,kasir'])->group(function () {
        Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\GuestController;

Route::get('/', [GuestController::class, 'dashboard'])->name('guest.dashboard');

// Smart redirects for dashboard URLs
Route::get('/cashier', function () {
    if (!session('authenticated') || !session('role')) {
        return redirect('/');
    }
    if (session('role') === 'cashier') {
        return redirect()->route('pos.index');
    }
    return redirect('/dashboard/' . session('role'));
});

Route::get('/supervisor', function () {
    if (!session('authenticated') || !session('role')) {
        return redirect('/');
    }
    if (session('role') === 'supervisor') {
        return redirect()->route('supervisor.index');
    }
    return redirect('/dashboard/' . session('role'));
});

Route::get('/administrator', function () {
    if (!session('authenticated') || !session('role')) {
        return redirect('/');
    }
    if (session('role') === 'administrator') {
        return redirect()->route('administrator.index');
    }
    return redirect('/dashboard/' . session('role'));
});

// Authentication routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// Email verification routes
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

// Password reset routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Dashboard routes
Route::get('/dashboard/{role}', [AuthController::class, 'dashboard']);

// POS routes (Cashier - Read Only)
Route::prefix('pos')->name('pos.')->middleware(['session.auth', 'role:cashier'])->group(function () {
    Route::get('/', [POSController::class, 'index'])->name('index');
    Route::post('/process-sale', [POSController::class, 'processSale'])->name('process-sale');
    Route::get('/receipt/{saleId}', [POSController::class, 'printReceipt'])->name('receipt');
    Route::get('/inventory', [POSController::class, 'inventory'])->name('inventory');
    Route::get('/sales', [POSController::class, 'sales'])->name('sales');
    Route::get('/search-products', [POSController::class, 'searchProducts'])->name('search-products');
});

// Supervisor routes
Route::prefix('supervisor')->name('supervisor.')->middleware(['session.auth', 'role:supervisor'])->group(function () {
    Route::get('/', [SupervisorController::class, 'index'])->name('index');
    Route::get('/products', [SupervisorController::class, 'products'])->name('products');
    Route::get('/products/create', [SupervisorController::class, 'createProduct'])->name('create-product');
    Route::post('/products', [SupervisorController::class, 'storeProduct'])->name('store-product');
    Route::get('/products/{id}/edit', [SupervisorController::class, 'editProduct'])->name('edit-product');
    Route::put('/products/{id}', [SupervisorController::class, 'updateProduct'])->name('update-product');
    Route::delete('/products/{id}', [SupervisorController::class, 'deleteProduct'])->name('delete-product');
    Route::get('/sales-reports', [SupervisorController::class, 'salesReports'])->name('sales-reports');
    Route::get('/low-stock', [SupervisorController::class, 'lowStock'])->name('low-stock');
    Route::put('/products/{id}/stock', [SupervisorController::class, 'updateStock'])->name('update-stock');
    Route::get('/price-override', [SupervisorController::class, 'priceOverride'])->name('price-override');
    Route::put('/products/{id}/price', [SupervisorController::class, 'updatePrice'])->name('update-price');
    Route::get('/team-performance', [SupervisorController::class, 'teamPerformance'])->name('team-performance');
    // Sales management (Supervisor only)
    Route::get('/sales', [SupervisorController::class, 'sales'])->name('sales');
    Route::get('/sales/{id}/edit', [POSController::class, 'editSale'])->name('edit-sale');
    Route::put('/sales/{id}', [POSController::class, 'updateSale'])->name('update-sale');
    Route::delete('/sales/{id}', [POSController::class, 'deleteSale'])->name('delete-sale');
});

// Administrator routes
Route::prefix('administrator')->name('administrator.')->middleware(['session.auth', 'role:administrator'])->group(function () {
    Route::get('/', [AdministratorController::class, 'index'])->name('index');
    // User management
    Route::get('/users', [AdministratorController::class, 'users'])->name('users');
    Route::get('/users/create', [AdministratorController::class, 'createUser'])->name('create-user');
    Route::post('/users', [AdministratorController::class, 'storeUser'])->name('store-user');
    Route::get('/users/{id}/edit', [AdministratorController::class, 'editUser'])->name('edit-user');
    Route::put('/users/{id}', [AdministratorController::class, 'updateUser'])->name('update-user');
    Route::delete('/users/{id}', [AdministratorController::class, 'deleteUser'])->name('delete-user');
    // System analytics
    Route::get('/analytics', [AdministratorController::class, 'systemAnalytics'])->name('analytics');
    Route::get('/logs', [AdministratorController::class, 'systemLogs'])->name('logs');
    Route::get('/logs/export/csv', [AdministratorController::class, 'exportLogsCsv'])->name('export-logs-csv');
    Route::get('/logs/export/json', [AdministratorController::class, 'exportLogsJson'])->name('export-logs-json');
    Route::post('/logs/clear', [AdministratorController::class, 'clearOldLogs'])->name('clear-old-logs');
    Route::get('/settings', [AdministratorController::class, 'systemSettings'])->name('settings');
    Route::put('/settings', [AdministratorController::class, 'updateSettings'])->name('update-settings');
    // Inherit all supervisor functions
    Route::get('/products', [AdministratorController::class, 'products'])->name('products');
    Route::get('/products/create', [AdministratorController::class, 'createProduct'])->name('create-product');
    Route::post('/products', [AdministratorController::class, 'storeProduct'])->name('store-product');
    Route::get('/products/{id}/edit', [AdministratorController::class, 'editProduct'])->name('edit-product');
    Route::put('/products/{id}', [AdministratorController::class, 'updateProduct'])->name('update-product');
    Route::delete('/products/{id}', [AdministratorController::class, 'deleteProduct'])->name('delete-product');
    Route::get('/sales-reports', [AdministratorController::class, 'salesReports'])->name('sales-reports');
    Route::get('/low-stock', [AdministratorController::class, 'lowStock'])->name('low-stock');
    Route::put('/products/{id}/stock', [AdministratorController::class, 'updateStock'])->name('update-stock');
    Route::get('/price-override', [AdministratorController::class, 'priceOverride'])->name('price-override');
    Route::put('/products/{id}/price', [AdministratorController::class, 'updatePrice'])->name('update-price');
    Route::get('/team-performance', [AdministratorController::class, 'teamPerformance'])->name('team-performance');
    Route::get('/sales', [AdministratorController::class, 'sales'])->name('sales');
    Route::get('/sales/{id}/edit', [POSController::class, 'editSale'])->name('edit-sale');
    Route::put('/sales/{id}', [POSController::class, 'updateSale'])->name('update-sale');
    Route::delete('/sales/{id}', [POSController::class, 'deleteSale'])->name('delete-sale');
});

// 2FA routes
Route::get('/2fa', [TwoFactorController::class, 'show2faForm'])->name('2fa.form');
Route::post('/2fa', [TwoFactorController::class, 'verify2fa'])->name('2fa.verify');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest:admin,client')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('admins', \App\Http\Controllers\Admin\AdminController::class);
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
        
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::get('orders/{order}/bill', [\App\Http\Controllers\Admin\OrderController::class, 'bill'])->name('orders.bill');
        Route::put('orders/{order}/price', [\App\Http\Controllers\Admin\OrderController::class, 'assignPrice'])->name('orders.assign-price');
        Route::delete('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
        
        Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::put('payments/{order}', [\App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
        
        Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [\App\Http\Controllers\Admin\SettingController::class, 'updateEmailSettings'])->name('settings.update-email');
        Route::post('settings/test-email', [\App\Http\Controllers\Admin\SettingController::class, 'sendTestEmail'])->name('settings.test-email');

        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/orders', [\App\Http\Controllers\Admin\ReportController::class, 'fetchOrders'])->name('reports.orders');
    });
});

// Client Routes
Route::prefix('client')->name('client.')->group(function () {
    Route::middleware('auth:client')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('orders', [\App\Http\Controllers\Client\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [\App\Http\Controllers\Client\OrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [\App\Http\Controllers\Client\OrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{order}/edit', [\App\Http\Controllers\Client\OrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{order}', [\App\Http\Controllers\Client\OrderController::class, 'update'])->name('orders.update');
        Route::delete('orders/{order}', [\App\Http\Controllers\Client\OrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('orders/{order}/bill', [\App\Http\Controllers\Client\OrderController::class, 'bill'])->name('orders.bill');
        
        Route::put('payment/{order}', [\App\Http\Controllers\Client\PaymentController::class, 'pay'])->name('payment.pay');
        
        Route::get('profile', [\App\Http\Controllers\Client\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\Client\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

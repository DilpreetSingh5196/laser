<?php

use Illuminate\Support\Facades\Route;

// Redirect root to client login
Route::get('/', function () {
    return redirect()->route('client.login');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
        
        Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('admins', \App\Http\Controllers\Admin\AdminController::class);
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
        
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/price', [\App\Http\Controllers\Admin\OrderController::class, 'assignPrice'])->name('orders.assign-price');
        
        Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::put('payments/{order}', [\App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
        
        Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

// Client Routes
Route::prefix('client')->name('client.')->group(function () {
    
    Route::middleware('guest:client')->group(function () {
        Route::get('login', [\App\Http\Controllers\Client\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [\App\Http\Controllers\Client\AuthController::class, 'login']);
    });

    Route::middleware('auth:client')->group(function () {
        Route::post('logout', [\App\Http\Controllers\Client\AuthController::class, 'logout'])->name('logout');
        
        Route::get('dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('orders', [\App\Http\Controllers\Client\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [\App\Http\Controllers\Client\OrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [\App\Http\Controllers\Client\OrderController::class, 'store'])->name('orders.store');
        
        Route::put('payment/{order}', [\App\Http\Controllers\Client\PaymentController::class, 'pay'])->name('payment.pay');
        
        Route::get('profile', [\App\Http\Controllers\Client\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\Client\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

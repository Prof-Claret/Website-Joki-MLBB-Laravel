<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\SiteSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{order}/progress', [OrderController::class, 'updateProgress'])->name('orders.progress');
    Route::post('/orders/{order}/proof', [OrderController::class, 'uploadProof'])->name('orders.proof');
    Route::post('/orders/{order}/review', [OrderController::class, 'storeReview'])->name('orders.review');
});

Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/ranks', [RankController::class, 'index'])->name('ranks.index');
    Route::get('/ranks/create', [RankController::class, 'create'])->name('ranks.create');
    Route::post('/ranks', [RankController::class, 'store'])->name('ranks.store');
    Route::get('/ranks/{rank}', [RankController::class, 'show'])->name('ranks.show');
    Route::get('/ranks/{rank}/edit', [RankController::class, 'edit'])->name('ranks.edit');
    Route::put('/ranks/{rank}', [RankController::class, 'update'])->name('ranks.update');
    Route::delete('/ranks/{rank}', [RankController::class, 'destroy'])->name('ranks.destroy');

    Route::get('/settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SiteSettingController::class, 'store'])->name('settings.store');
    Route::put('/settings/{setting}', [SiteSettingController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
});

Route::middleware(['auth', 'role:worker'])->prefix('worker')->name('worker.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/progress', [OrderController::class, 'updateProgress'])->name('orders.progress');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

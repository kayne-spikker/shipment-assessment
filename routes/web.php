<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/orders', [OrderController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('orders');

Route::get('/orders/{order}', [OrderController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('orders.show');

Route::post('/orders/{order}/create-shipment',
    [ShipmentController::class, 'create']
)->middleware(['auth'])->name('orders.shipment.create');

Route::get('/download-pdf/{order}',
    [ShipmentController::class, 'downloadPDF']
)->name('shipment.download');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

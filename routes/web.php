<?php

use App\Http\Controllers\Collections\CollectionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Маршруты для коллекций
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::get('collections/create', [CollectionController::class, 'create'])->name('collections.create');
    Route::post('collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::get('collections/{collection:slug}', [CollectionController::class, 'show'])->name('collections.show');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

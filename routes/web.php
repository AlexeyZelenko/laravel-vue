<?php

use App\Http\Controllers\Collections\CollectionController;
use App\Http\Controllers\Collections\CollectionItemController;
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
    // Коллекции
    Route::get('collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::get('collections/create', [CollectionController::class, 'create'])->name('collections.create');
    Route::post('collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::get('collections/{collection:slug}', [CollectionController::class, 'show'])->name('collections.show');

    // Элементы коллекций
    Route::get('collections/{collection:slug}/items/create', [CollectionItemController::class, 'create'])->name('collections.items.create');
    Route::post('collections/{collection:slug}/items', [CollectionItemController::class, 'store'])->name('collections.items.store');
    Route::get('/collections/{collection}/items', [CollectionItemController::class, 'index']);
});

// Fruit routes
Route::get('fruits', function () {
    return Inertia::render('fruits/Index');
})->name('fruits.index');

Route::get('/fruits/family/{family}', function ($family) {
    return Inertia::render('fruits/Family', [
        'family' => $family,
    ]);
})->name('fruits.family');

Route::get('fruits/favorites', function () {
    return Inertia::render('fruits/Favorites');
})->name('fruits.favorites');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AllergenController;

// Login / logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin ala – ainult sisse loginud kasutajatele
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
// Avaleht – menüü kuvamine kõigile (kasutaja pool)
// Dashboard – ainult sisse logitud kasutajale
Route::middleware('auth')->get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::middleware('auth')->resource('menus', \App\Http\Controllers\Admin\MenuController::class);

    // Kategooriate haldus
    Route::get('/dashboard/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::get('/dashboard/categories/create', [CategoryController::class, 'create'])
        ->name('categories.create');

    Route::post('/dashboard/categories', [CategoryController::class, 'store'])
        ->name('categories.store');

    
    Route::get('/dashboard/categories/{category}/edit', [CategoryController::class, 'edit'])
        ->name('categories.edit');

    
    Route::put('/dashboard/categories/{category}', [CategoryController::class, 'update'])
        ->name('categories.update');

    Route::delete('/dashboard/categories/{category}', [CategoryController::class, 'destroy'])
    ->name('categories.destroy');

});
// Avalik menüü
Route::get('/', [MenuController::class, 'show'])->name('menu');

// ---- Ainult sisse logitud kasutajatele (admini pool) ----
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Allergeenide haldus
    Route::get('/allergens', [AllergenController::class, 'index'])->name('allergens.index');
    Route::post('/allergens', [AllergenController::class, 'store'])->name('allergens.store');
    Route::delete('/allergens/{allergen}', [AllergenController::class, 'destroy'])->name('allergens.destroy');

    // Järjekorra muutmine ↑ / ↓
    Route::post('/allergens/{allergen}/up', [AllergenController::class, 'moveUp'])->name('allergens.move_up');
    Route::post('/allergens/{allergen}/down', [AllergenController::class, 'moveDown'])->name('allergens.move_down');
});

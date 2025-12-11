<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AllergenController;

// Login / logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Avaleht – menüü kuvamine kõigile (kasutaja pool)
// Dashboard – ainult sisse logitud kasutajale
Route::middleware('auth')->get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::middleware('auth')->resource('menus', \App\Http\Controllers\Admin\MenuController::class);

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

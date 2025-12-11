<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin ala – ainult sisse loginud kasutajatele
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

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

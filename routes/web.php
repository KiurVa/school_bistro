<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;                         // avalik menüü
use App\Http\Controllers\Admin\MenuController as AdminMenu;      // admin menüü
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AllergenController;
use App\Http\Controllers\UserManagementController;


// ------------------------------
// Avalik (kasutaja) vaade
// ------------------------------
Route::get('/', [MenuController::class, 'show'])->name('menu');


// ------------------------------
// Autentimine
// ------------------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ------------------------------
// ADMIN ALA – ainult sisse logitud kasutajatele
// ------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // --------------------------
    // Menüü haldus (admin)
    // --------------------------
    Route::resource('menus', AdminMenu::class);

    // --------------------------
    // Kategooriate haldus
    // --------------------------
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{category}/up', [CategoryController::class, 'moveUp'])->name('categories.move_up');
    Route::post('/categories/{category}/down', [CategoryController::class, 'moveDown'])->name('categories.move_down');

    // --------------------------
    // Allergeenide haldus
    // --------------------------
    Route::get('/allergens', [AllergenController::class, 'index'])->name('allergens.index');
    Route::post('/allergens', [AllergenController::class, 'store'])->name('allergens.store');
    Route::get('/allergens/{allergen}/edit', [AllergenController::class, 'edit'])->name('allergens.edit');
    Route::put('/allergens/{allergen}', [AllergenController::class, 'update'])->name('allergens.update');
    Route::delete('/allergens/{allergen}', [AllergenController::class, 'destroy'])->name('allergens.destroy');

    // Järjekorra muutmine ↑ ↓
    Route::post('/allergens/{allergen}/up', [AllergenController::class, 'moveUp'])->name('allergens.move_up');
    Route::post('/allergens/{allergen}/down', [AllergenController::class, 'moveDown'])->name('allergens.move_down');

    // --------------------------
    // Kasutajate haldus (AINULT ADMIN)
    // Admin-kontroller kontrollib is_admin staatust
    // --------------------------
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

});

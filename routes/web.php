<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;

//user routes

Route::get('/', [UserController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'redirectAdmin',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

//add to cart route

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('view', 'view')->name('cart.view');
    Route::post('store/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('update/{prodcut}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('delete/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
});

//admin routes- no auth required here
Route::group(['prefix' => 'admin',], function () {
    Route::get('/login', [AdminAuthController::class, 'showloginform'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

//admin routes- this routes are protected

Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('brands', AdminBrandController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::delete('/products/image/{id}',[AdminProductController::class,'deleteImage'])->name('admin.products.image.delete');
});

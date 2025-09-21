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
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminBestSellerController;

//user routes

Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/products/{id}', [UserController::class, 'show'])->name('products.show');
Route::get('/categories/{slug}', [UserController::class, 'category'])->name('categories.show');

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
    Route::resource('brands', AdminBrandController::class)->names('admin.brands');
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
    Route::resource('products', AdminProductController::class)->names('admin.products');
    Route::delete('/products/image/{id}',[AdminProductController::class,'deleteImage'])->name('admin.products.image.delete');
    Route::resource('users', AdminUserController::class)->names('admin.users');
    Route::resource('orders', AdminOrderController::class)->names('admin.orders');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status.update');
    Route::resource('bestsellers', AdminBestSellerController::class)->names('admin.bestsellers');
});

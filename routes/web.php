<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserAddressController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminBestSellerController;
use App\Http\Controllers\Admin\AdminCollectionController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\PaymentController;

//user routes

Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/products', [UserController::class, 'products'])->name('products.index');
Route::get('/products/{id}', [UserController::class, 'show'])->name('products.show');
Route::get('/categories/{slug}', [UserController::class, 'category'])->name('categories.show');
Route::get('/collections/{slug}', [UserController::class, 'collection'])->name('collections.show');

// Static pages
Route::get('/privacy', function () {
    return Inertia::render('User/Privacy');
})->name('privacy');

Route::get('/terms', function () {
    return Inertia::render('User/Terms');
})->name('terms');

Route::get('/contact', function () {
    return Inertia::render('User/Contact');
})->name('contact');

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
    Route::patch('update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('delete/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
});

//checkout routes
Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
    Route::get('index', 'index')->name('checkout.index');
    Route::post('process', 'process')->name('checkout.process');
    Route::get('payment/{order}', 'payment')->name('checkout.payment')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified']);
    Route::get('success', 'success')->name('checkout.success');
    Route::get('cancel', 'cancel')->name('checkout.cancel');
});

//user orders routes
Route::prefix('orders')->controller(\App\Http\Controllers\User\OrderController::class)->group(function () {
    Route::get('/', 'index')->name('orders.index');
    Route::get('/{id}', 'show')->name('orders.show');
});

//payment routes
Route::prefix('payments')->controller(PaymentController::class)->group(function () {
    Route::get('methods', 'getPaymentMethods')->name('payments.methods');
    Route::get('status/{transactionId}', 'checkPaymentStatus')->name('payments.status');
    Route::post('mpesa/initiate', 'initiateMpesaPayment')->name('payments.mpesa.initiate')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified']);
    // M-Pesa callback (no auth)
    Route::post('mpesa/callback', 'handleMpesaCallback')->name('payments.mpesa.callback');
    Route::post('initiate', 'initiatePayment')->name('payments.initiate')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified']);
});

//user address routes
Route::prefix('addresses')->controller(UserAddressController::class)->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('create', 'create')->name('addresses.create');
    Route::post('store', 'store')->name('addresses.store');
});

//admin routes- no auth required here
Route::group(['prefix' => 'admin',], function () {
    Route::get('/login', [AdminAuthController::class, 'showloginform'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

//admin routes- this routes are protected

Route::prefix('admin')->middleware(['auth','admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('brands', AdminBrandController::class)->names('admin.brands');
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
    Route::resource('products', AdminProductController::class)->names('admin.products');
    Route::delete('/products/image/{id}',[AdminProductController::class,'deleteImage'])->name('admin.products.image.delete');
    Route::resource('users', AdminUserController::class)->names('admin.users');
    Route::resource('orders', AdminOrderController::class)->names('admin.orders');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status.update');
    Route::resource('bestsellers', AdminBestSellerController::class)->names('admin.bestsellers');
    Route::resource('collections', AdminCollectionController::class)->names('admin.collections');
    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
});

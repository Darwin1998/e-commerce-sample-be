<?php

declare(strict_types=1);

use App\Http\Controllers\API\Customers\AddressController;
use App\Http\Controllers\API\Customers\AuthController;
use App\Http\Controllers\API\Customers\ProfileController;
use App\Http\Controllers\API\Products\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1/customer')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('customer.login'); // ✅ Add a name to the login route
    Route::post('/register', [AuthController::class, 'register'])->name('customer.register'); // ✅ Add a name to the login route

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('customer.logout');
        Route::post('/password', [AuthController::class, 'changePassword'])->name('customer.password.change');

        Route::get('/profile', [ProfileController::class, 'getProfile'])->name('customer.profile');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('customer.profile.update');

        Route::get('/address', [AddressController::class, 'index'])->name('customer.address');
        Route::post('/address', [AddressController::class, 'store'])->name('customer.address.store');
    });
});

Route::prefix('v1/products')->group(function () {
    Route::get('/all', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/show/{product}', [ProductsController::class, 'show'])->name('products.show');

});

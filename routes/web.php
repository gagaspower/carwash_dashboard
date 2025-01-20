<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleBrandController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/auth/login-proses', '_create_session');
    Route::get('/auth/register', '_register');
    Route::post('/auth/register/store', 'store_register');
});
Route::middleware(['auth'])->group(function () {
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer', 'index');
        Route::get('/customer/add', 'create');
        Route::get('/customer/fetch-customer', '_fetch_customer');
        Route::post('/customer/add/store', 'store');
        Route::get('/customer/edit/{id}', 'edit');
        Route::put('/customer/update/{id}', 'update');
        Route::delete('/customer/delete/{id}', 'destroy');
    });

    Route::controller(VehicleBrandController::class)->group(function () {
        Route::get('/merk-kendaraan', 'index');
        Route::get('/merk-kendaraan/fetch-data', '_fetch_data');
        Route::get('/merk-kendaraan/add', 'create');
        Route::post('/merk-kendaraan/add/store', 'store');
        Route::get('/merk-kendaraan/edit/{id}', 'edit');
        Route::put('/merk-kendaraan/update/{id}', 'update');
        Route::delete('/merk-kendaraan/delete/{id}', 'destroy');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('/role', 'index');
        Route::get('/role/get-data', '_fetch_data');
        Route::get('/role/edit/{id}', 'edit');
        Route::post('/role/update/{id}', 'update');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index');
        Route::get('/user/fetch-user', '_fetch_user');
        Route::get('/user/add', 'create');
        Route::post('/user/add/store', 'store');
        Route::get('/user/edit/{id}', 'edit');
        Route::put('/user/update/{id}', 'update');
        Route::delete('/user/delete/{id}', 'destroy');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index');
        Route::get('/product/fetch-data', '_fetch_data');
        Route::get('/product/add', 'create');
        Route::post('/product/add/store', 'store');
        Route::get('/product/edit/{id}', 'edit');
        Route::put('/product/update/{id}', 'update');
        Route::delete('/product/delete/{id}', 'destroy');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::get('/auth/signout', 'logout');
    });
});

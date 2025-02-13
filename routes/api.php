<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'signOut']);

    Route::get('/current-auth', function (Request $request) {
        return $request->user();
    });

    Route::controller(ExpenseController::class)->group(function () {
        Route::get('/pengeluaran', 'index');
        Route::post('/pengeluaran/add', 'store');
        Route::get('/pengeluaran/detail/{id}', 'show');
        Route::put('/pengeluaran/update/{id}', 'update');
        Route::delete('/pengeluaran/delete/{id}', 'destroy');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index');
        Route::post('/product/add', 'store');
        Route::get('/product/detail/{id}', 'show');
        Route::put('/product/update/{id}', 'update');
        Route::delete('/product/delete/{id}', 'destroy');
    });

    Route::controller(PegawaiController::class)->group(function () {
        Route::get('/pegawai', 'index');
        Route::post('/pegawai/add', 'store');
        Route::get('/pegawai/edit/{id}', 'show');
        Route::put('/pegawai/update/{id}', 'update');
        Route::delete('/pegawai/delete/{id}', 'destroy');
    });

    Route::get('/auth/current-auth', [AuthController::class, 'currentAuth']);
    Route::post('/auth/change-password', [AuthController::class, 'changePwd']);

    Route::get('/user/all', [UserController::class, 'index']);

    Route::controller(CustomerController::class)->group(function () {
        Route::get('/pelanggan', 'index');
        Route::post('/pelanggan/add', 'store');
        Route::get('/pelanggan/detail/{id}', 'show');
        Route::put('/pelanggan/update/{id}', 'update');
        Route::delete('/pelanggan/delete/{id}', 'destroy');
    });
});

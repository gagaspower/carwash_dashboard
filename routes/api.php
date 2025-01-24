<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\ProductController;
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

    Route::get('/auth/current-auth', [AuthController::class, 'currentAuth']);
});

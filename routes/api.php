<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return response()->json(['message' => 'INAIA Trading API. hello']);
});


Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/', function (Request $request) {
        return response()->json(['message' => 'INAIA Trading API.eerere']);
    });

    Route::post('/login', [UserController::class, 'login'])->name('user.login');
    Route::post('/register', [UserController::class, 'register'])->name('user.register');
    Route::middleware(['auth:api'])->group(function () {
        Route::apiResource('user', UserController::class);
        Route::apiResource('place', \App\Http\Controllers\PlaceController::class);
        Route::apiResource('floor', \App\Http\Controllers\FloorController::class);
        Route::apiResource('category', \App\Http\Controllers\CategoryController::class);
        Route::apiResource('slot', \App\Http\Controllers\SlotController::class);
        Route::apiResource('tariff', \App\Http\Controllers\TariffController::class);
        Route::apiResource('parking', \App\Http\Controllers\ParkingController::class);
        Route::apiResource('parking-rate', \App\Http\Controllers\ParkingRateController::class);
    });
});

<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return response()->json(['message' => 'Hello World!'], 200);
});
Route::get('/migrate', function (Request $request) {
    Artisan::call('migrate');

    return 'Migration completed successfully.';
});
Route::get('/migrate-fresh', function (Request $request) {
    Artisan::call('migrate:fresh');
    return 'Migration fresh successfully.';
});

Route::get('/cleareverything', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:clear');
    $clearconfig = Artisan::call('passport:install');
    return "Config cleared<br>";
});

Route::get('/install-passport', function () {
    // Execute the passport:install Artisan command
    Artisan::call('passport:install');

    // Capture the output of the command
    $output = Artisan::output();

    // Output a message indicating the installation
    echo "Passport installed: <br>";
    echo $output;
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

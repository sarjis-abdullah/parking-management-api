<?php

use App\Enums\RolesAndPermissions;
use App\Http\Controllers\UserController;
use App\Models\Parking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

Route::get('/', function (Request $request) {
    return response()->json(['message' => 'Hello World!'], 200);
});
Route::get('/migrate', function (Request $request) {
    Artisan::call('migrate', array('--force' => true));

    return 'Migration completed successfully.';
});
Route::get('/install', function (Request $request) {
    $user = \App\Models\User::where('id', '!=', null)->first();

    Artisan::call('migrate:fresh', array('--force' => true));


    if ($user == null)
        Artisan::call('db:seed');

    define('STDIN',fopen("php://stdin","r"));
    Artisan::call('passport:install', [
        '--force' => true
    ]);
    Artisan::call('passport:client --personal');
    return 'Installation completed successfully.';
});
Route::get('/migrate-fresh', function (Request $request) {
    Artisan::call('migrate:fresh', array('--force' => true));
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

Route::get('/db:seed', function () {
    Artisan::call('db:seed');
    // Capture the output of the command
    $output = Artisan::output();

    // Output a message indicating the installation
    echo "Database seeded: <br>";
    echo $output;
});Route::get('/report-view', function () {
//    return view('parking-report');
    return view('format-1');
});

Route::get('/re', function () {
//    $startDate = Carbon::parse('2024-01-01');
//    $endDate = Carbon::parse('2024-07-31');
//
//    $dateWiseVehicleEntries = Parking::select(DB::raw('DATE(in_time) as entry_date'), DB::raw('COUNT(id) as vehicle_entries'))
//        ->whereNotNull('in_time')
//        ->whereBetween('in_time', [$startDate, $endDate])
//        ->groupBy('entry_date')
//        ->orderBy('entry_date')
//        ->get();
//
//    $dateWiseTransactions = Payment::select(DB::raw('DATE(created_at) as transaction_date'), DB::raw('COUNT(id) as transaction_count'), DB::raw('SUM(payable_amount) as total_payable'), DB::raw('SUM(paid_amount) as total_paid'), DB::raw('SUM(due_amount) as total_due'))
//        ->whereBetween('created_at', [$startDate, $endDate])
//        ->groupBy('transaction_date')
//        ->orderBy('transaction_date')
//        ->get();
//
//    return response()->json([
//        'dateWiseVehicleEntries'=> $dateWiseVehicleEntries,
//        'dateWiseTransactions'=> $dateWiseTransactions,
//    ]);
});

Route::get('/install-passport', function () {
    //before this, added below lines to auth service provider
    /*
     $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);
     */
    define('STDIN',fopen("php://stdin","r"));
    // Execute the passport:install Artisan command
    Artisan::call('passport:install', [
        '--force' => true
    ]);
    Artisan::call('passport:client --personal');

    // Capture the output of the command
//    $output = Artisan::output();

    // Output a message indicating the installation
    echo "Passport installed: <br>";
//    echo $output;
});


Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/', function (Request $request) {
        return response()->json(['message' => 'Hello API']);
    });

    Route::post('/login', [UserController::class, 'login'])->name('user.login');
    Route::post('/register', [UserController::class, 'register'])->name('user.register');
    Route::middleware(['auth:api'])->group(function () {

        Route::middleware(
            ['role:'.RolesAndPermissions::ADMIN.'|'.RolesAndPermissions::OPERATOR.'|'.RolesAndPermissions::SUPER_ADMIN]
        )->group(function () {
            Route::apiResource('membership', \App\Http\Controllers\MembershipController::class);
            Route::apiResource('vehicle', \App\Http\Controllers\VehicleController::class);
            Route::apiResource('parking', \App\Http\Controllers\ParkingController::class);
            Route::apiResource('parking-rate', \App\Http\Controllers\ParkingRateController::class);

            Route::put('parking-check-out/{parking}', [\App\Http\Controllers\ParkingController::class, 'handleCheckout']);
            Route::get('place', [\App\Http\Controllers\PlaceController::class, 'index']);
            Route::get('floor', [\App\Http\Controllers\FloorController::class, 'index']);
            Route::get('category', [\App\Http\Controllers\CategoryController::class, 'index']);
            Route::get('slot', [\App\Http\Controllers\SlotController::class, 'index']);
            Route::get('tariff', [\App\Http\Controllers\TariffController::class, 'index']);
        });

        Route::middleware(
            ['role:'.RolesAndPermissions::ADMIN.'|'.RolesAndPermissions::SUPER_ADMIN]
        )->group(function () {

            Route::group(['prefix' => 'report'], function () {
                Route::get('/transaction', [\App\Http\Controllers\ReportController::class, 'getTransactionReport'])->name('transaction.report');
                Route::get('/vehicle', [\App\Http\Controllers\ReportController::class, 'getVehicleReport'])->name('vehicle.report');
            });
            Route::apiResource('user', UserController::class);
            Route::apiResource('place', \App\Http\Controllers\PlaceController::class)->except('index');
            Route::apiResource('floor', \App\Http\Controllers\FloorController::class)->except('index');
            Route::apiResource('category', \App\Http\Controllers\CategoryController::class)->except('index');
            Route::apiResource('slot', \App\Http\Controllers\SlotController::class)->except('index');
            Route::apiResource('tariff', \App\Http\Controllers\TariffController::class)->except('index');
        });

    });
});

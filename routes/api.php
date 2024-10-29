<?php

use App\Enums\RolesAndPermissions;
use App\Http\Controllers\SslCommerzPaymentController;
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
    Artisan::call('db:seed');
    Artisan::call('storage:link');

//    if ($user == null)
//    {
//        Artisan::call('db:seed');
//        dump('Database seed completed successfully.');
//    }

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


Route::group(['prefix' => 'web/v1'], function () {
    // SSLCOMMERZ Start
    Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
    Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

    Route::post('/pay', [SslCommerzPaymentController::class, 'pay']);
    Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

//    Route::post('/success', [SslCommerzPaymentController::class, 'success']);
//    Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
//    Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
//
//    Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
});
Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/', function (Request $request) {
        return response()->json(['message' => 'Hello API']);
    });

    Route::group(['prefix' => 'payment'], function () {
        // SSLCOMMERZ Start
//        Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
//        Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

        Route::post('/pay', [SslCommerzPaymentController::class, 'pay']);
        Route::get('/repay/{paymentId}', [\App\Http\Controllers\ParkingController::class, 'repay'])->name('repay');
        Route::get('/scan/repay/{paymentId}', [\App\Http\Controllers\ParkingController::class, 'repay'])->name('scan.repay');
        Route::get('/pay-due/{paymentId}', [\App\Http\Controllers\ParkingController::class, 'payDue'])->name('payDue');
        Route::get('/scan/pay-due/{paymentId}', [\App\Http\Controllers\ParkingController::class, 'payDue'])->name('scan.payDue');
        Route::post('/pay-all-due', [\App\Http\Controllers\ParkingController::class, 'payAllDue'])->name('pay-all-due');
//        Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

        Route::post('/success/{transactionId}', [SslCommerzPaymentController::class, 'success']);
        Route::post('/fail/{transactionId}', [SslCommerzPaymentController::class, 'fail']);
        Route::post('/cancel/{transactionId}', [SslCommerzPaymentController::class, 'cancel']);

        Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);



    });
    Route::get('parking', [\App\Http\Controllers\ParkingController::class, 'index']);
    Route::put('parking-check-out/{parking}', [\App\Http\Controllers\ParkingController::class, 'handleCheckout']);
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
    Route::post('/register', [UserController::class, 'register'])->name('user.register');
    Route::middleware(['auth:api'])->group(function () {

        Route::middleware(
            ['role:'.RolesAndPermissions::ADMIN.'|'.RolesAndPermissions::OPERATOR.'|'.RolesAndPermissions::SUPER_ADMIN]
        )->group(function () {
            Route::apiResource('membership', \App\Http\Controllers\MembershipController::class);
            Route::apiResource('vehicle', \App\Http\Controllers\VehicleController::class);
            Route::apiResource('parking', \App\Http\Controllers\ParkingController::class)->except('index');
            Route::apiResource('parking-rate', \App\Http\Controllers\ParkingRateController::class);
            Route::apiResource('cash-flow', \App\Http\Controllers\CashFlowController::class);


            Route::get('place', [\App\Http\Controllers\PlaceController::class, 'index']);
            Route::get('floor', [\App\Http\Controllers\FloorController::class, 'index']);
            Route::get('category', [\App\Http\Controllers\CategoryController::class, 'index']);
            Route::get('slot', [\App\Http\Controllers\SlotController::class, 'index']);
            Route::get('block', [\App\Http\Controllers\BlockController::class, 'index']);
            Route::get('tariff', [\App\Http\Controllers\TariffController::class, 'index']);
            Route::put('payment/{payment}', [\App\Http\Controllers\PaymentController::class, 'update']);
            Route::get('payment', [\App\Http\Controllers\PaymentController::class, 'index']);
            Route::get('discount', [\App\Http\Controllers\DiscountController::class, 'index']);
            Route::get('close-cash', [\App\Http\Controllers\CashFlowController::class, 'endDay']);
        });

        Route::middleware(
            ['role:'.RolesAndPermissions::ADMIN.'|'.RolesAndPermissions::SUPER_ADMIN]
        )->group(function () {

            Route::group(['prefix' => 'report'], function () {
                Route::get('/transaction', [\App\Http\Controllers\ReportController::class, 'getTransactionReport'])->name('transaction.report');
                Route::get('/vehicle', [\App\Http\Controllers\ReportController::class, 'getVehicleReport'])->name('vehicle.report');
                Route::get('/slot', [\App\Http\Controllers\ReportController::class, 'getSlotReport'])->name('slot.report');
                Route::get('/vehicle-details', [\App\Http\Controllers\ReportController::class, 'getDetailVehicleReport'])->name('details.vehicle.report');
            });
            Route::apiResource('user', UserController::class);
            Route::apiResource('place', \App\Http\Controllers\PlaceController::class)->except('index');
            Route::apiResource('floor', \App\Http\Controllers\FloorController::class)->except('index');
            Route::apiResource('block', \App\Http\Controllers\BlockController::class)->except('index');
            Route::apiResource('category', \App\Http\Controllers\CategoryController::class)->except('index');
            Route::apiResource('slot', \App\Http\Controllers\SlotController::class)->except('index');
            Route::apiResource('tariff', \App\Http\Controllers\TariffController::class)->except('index');
            Route::apiResource('discount', \App\Http\Controllers\DiscountController::class)->except('index');
            Route::apiResource('membership-type', \App\Http\Controllers\MembershipTypeController::class);
        });
    });
});

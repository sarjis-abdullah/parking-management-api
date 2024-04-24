<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return response()->json(['message' => 'INAIA Trading API. hello']);
});


Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/', function (Request $request) {
        return response()->json(['message' => 'INAIA Trading API.eerere']);
    });

    Route::group(['prefix' => 'instruments'], function () {
        Route::get('/', 'InstrumentController@index');
        Route::get('/supported', 'InstrumentController@getListOfSupportedInstruments');
        Route::get('/{isn}', 'InstrumentController@show');
    });

    Route::group(['prefix' => 'trading-user'], function () {
        Route::post('/', 'TradingUserController@store');

        Route::group(['prefix' => 'check'], function () {
            Route::post('/{trading_user_id}/kyc', 'TradingUserCheckController@createKYCCheck');
            Route::post('/{trading_user_id}/if', 'TradingUserCheckController@createInstrumentFitCheck');
            Route::post('/{trading_user_id}/por', 'TradingUserCheckController@createProofOfResidencyCheck');
        });
    });

    Route::group(['prefix' => 'trading-account'], function () {

        Route::post('/', 'TradingAccountController@store');

        Route::group(['prefix' => 'group'], function () {
            Route::post('/', 'TradingAccountGroupController@store');
        });
    });

});

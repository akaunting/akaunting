<?php

Route::group(['middleware' => ['web', 'auth', 'language', 'customermenu', 'permission:read-customer-panel'], 'prefix' => 'customers', 'namespace' => 'Modules\PaypalStandard\Http\Controllers'], function () {
    Route::get('invoices/{invoice}/paypalstandard', 'PaypalStandard@show');
});

Route::group(['prefix' => 'customers', 'namespace' => 'Modules\PaypalStandard\Http\Controllers'], function () {
    Route::post('invoices/{invoice}/paypalstandard/result', 'PaypalStandard@result');
    Route::post('invoices/{invoice}/paypalstandard/callback', 'PaypalStandard@callback');
});

Route::group(['middleware' => ['web', 'language'], 'prefix' => 'links', 'namespace' => 'Modules\PaypalStandard\Http\Controllers'], function () {
    Route::group(['middleware' => 'signed-url'], function () {
        Route::post('invoices/{invoice}/paypalstandard', 'PaypalStandard@show');
    });
});

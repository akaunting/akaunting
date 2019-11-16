<?php

Route::group([
    'middleware' => 'customer',
    'prefix' => 'customers',
    'namespace' => 'Modules\PaypalStandard\Http\Controllers'
], function () {
    Route::get('invoices/{invoice}/paypalstandard', 'PaypalStandard@show');
});

Route::group([
    'prefix' => 'customers',
    'namespace' => 'Modules\PaypalStandard\Http\Controllers'
], function () {
    Route::post('invoices/{invoice}/paypalstandard/result', 'PaypalStandard@result');
    Route::post('invoices/{invoice}/paypalstandard/callback', 'PaypalStandard@callback');
});

Route::group([
    'middleware' => ['signed', 'language'],
    'prefix' => 'signed',
    'namespace' => 'Modules\PaypalStandard\Http\Controllers'
], function () {
    Route::post('invoices/{invoice}/paypalstandard', 'PaypalStandard@show');
});

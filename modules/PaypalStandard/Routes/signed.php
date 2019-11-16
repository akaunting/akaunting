<?php

Route::group([
    'prefix' => 'signed',
    'middleware' => 'signed',
    'namespace' => 'Modules\PaypalStandard\Http\Controllers'
], function () {
    Route::post('invoices/{invoice}/paypal-standard', 'Payment@show')->name('signed.invoices.paypal-standard.show');
});

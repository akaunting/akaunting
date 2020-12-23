<?php

Route::group([
    'prefix' => 'portal',
    'middleware' => 'guest',
    'namespace' => 'Modules\PaypalStandard\Http\Controllers'
], function () {
    Route::post('invoices/{document}/paypal-standard/return', 'Payment@return')->name('portal.invoices.paypal-standard.return');
    Route::post('invoices/{document}/paypal-standard/complete', 'Payment@complete')->name('portal.invoices.paypal-standard.complete');
});

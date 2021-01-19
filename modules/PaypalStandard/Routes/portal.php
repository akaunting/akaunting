<?php

Route::group([
    'prefix' => 'portal',
    'middleware' => 'portal',
    'namespace' => 'Modules\PaypalStandard\Http\Controllers'
], function () {
    Route::get('invoices/{invoice}/paypal-standard', 'Payment@show')->name('portal.invoices.paypal-standard.show');
});

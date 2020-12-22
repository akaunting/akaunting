<?php

Route::group([
    'prefix' => 'portal',
    'middleware' => 'portal',
    'namespace' => 'Modules\OfflinePayments\Http\Controllers'
], function () {
    Route::get('invoices/{invoice}/offline-payments', 'Payment@show')->name('portal.invoices.offline-payments.show');
    Route::post('invoices/{invoice}/offline-payments/confirm', 'Payment@confirm')->name('portal.invoices.offline-payments.confirm');
});

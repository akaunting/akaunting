<?php

Route::group([
    'prefix' => 'signed',
    'middleware' => 'signed',
    'namespace' => 'Modules\OfflinePayments\Http\Controllers'
], function () {
    Route::get('invoices/{document}/offline-payments', 'Payment@signed')->name('signed.invoices.offline-payments.show');
    Route::post('invoices/{document}/offline-payments/confirm', 'Payment@confirm')->name('signed.invoices.offline-payments.confirm');
});

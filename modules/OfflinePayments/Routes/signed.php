<?php

Route::group([
    'prefix' => 'signed',
    'middleware' => 'signed',
    'namespace' => 'Modules\OfflinePayments\Http\Controllers'
], function () {
    Route::get('invoices/{invoice}/offline-payments', 'Payment@signed')->name('signed.invoices.offline-payments.show');
    Route::post('invoices/{invoice}/offline-payments/confirm', 'Payment@confirm')->name('signed.invoices.offline-payments.confirm');
});

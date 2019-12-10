<?php

Route::group([
    'middleware' => 'admin',
    'prefix' => 'apps/offlinepayment',
    'namespace' => 'Modules\OfflinePayment\Http\Controllers'
], function () {
    Route::get('settings', 'Settings@edit')->name('offlinepayment.edit');
    Route::post('settings', 'Settings@update')->name('offlinepayment.update');
    Route::post('settings/get', 'Settings@get')->name('offlinepayment.get');
    Route::post('settings/delete', 'Settings@delete')->name('offlinepayment.delete');
});

Route::group([
    'middleware' => 'customer',
    'prefix' => 'customers',
    'namespace' => 'Modules\OfflinePayment\Http\Controllers'
], function () {
    Route::get('invoices/{invoice}/offlinepayment', 'OfflinePayment@show');
    Route::post('invoices/{invoice}/offlinepayment/confirm', 'OfflinePayment@confirm');
});

Route::group([
    'middleware' => ['signed', 'language'],
    'prefix' => 'signed',
    'namespace' => 'Modules\OfflinePayment\Http\Controllers'
], function () {
    Route::post('invoices/{invoice}/offlinepayment', 'OfflinePayment@link');
    Route::post('invoices/{invoice}/offlinepayment/confirm', 'OfflinePayment@confirm');
});

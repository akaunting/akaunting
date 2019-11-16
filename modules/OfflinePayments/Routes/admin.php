<?php

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\OfflinePayments\Http\Controllers'
], function () {
    Route::group(['prefix' => 'settings'], function () {
        Route::get('offline-payments', 'Settings@edit')->name('offline-payments.edit');
        Route::post('offline-payments', 'Settings@update')->name('offline-payments.update');
        Route::post('offline-payments/get', 'Settings@get')->name('offline-payments.get');
        Route::delete('offline-payments/delete', 'Settings@destroy')->name('offline-payments.delete');
    });
});

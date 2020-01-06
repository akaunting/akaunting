<?php

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\OfflinePayments\Http\Controllers'
], function () {
    Route::group(['prefix' => 'offline-payments/settings'], function () {
        Route::get('/', 'Settings@edit')->name('offline-payments.edit');
        Route::post('/', 'Settings@update')->name('offline-payments.update');
        Route::post('get', 'Settings@get')->name('offline-payments.get');
        Route::delete('delete', 'Settings@destroy')->name('offline-payments.delete');
    });
});

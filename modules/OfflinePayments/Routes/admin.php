<?php

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\OfflinePayments\Http\Controllers'
], function () {
    Route::group(['prefix' => 'offline-payments', 'as' => 'offline-payments.'], function () {
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::get('/', 'Settings@edit')->name('edit');
            Route::post('/', 'Settings@update')->name('update');
            Route::post('get', 'Settings@get')->name('get');
            Route::delete('delete', 'Settings@destroy')->name('delete');
        });
    });
});

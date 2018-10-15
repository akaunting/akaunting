<?php

Route::group(['middleware' => ['web', 'auth', 'language', 'adminmenu', 'permission:read-admin-panel'], 'prefix' => 'apps/offlinepayment', 'namespace' => 'Modules\OfflinePayment\Http\Controllers'], function () {
    Route::get('settings', 'Settings@edit');
    Route::post('settings', 'Settings@update');
    Route::post('settings/get', 'Settings@get');
    Route::post('settings/delete', 'Settings@delete');
});

Route::group(['middleware' => ['web', 'auth', 'language', 'customermenu', 'permission:read-customer-panel'], 'prefix' => 'customers', 'namespace' => 'Modules\OfflinePayment\Http\Controllers'], function () {
    Route::get('invoices/{invoice}/offlinepayment', 'OfflinePayment@show');
    Route::post('invoices/{invoice}/offlinepayment/confirm', 'OfflinePayment@confirm');
});

Route::group(['middleware' => ['web', 'language'], 'prefix' => 'links', 'namespace' => 'Modules\OfflinePayment\Http\Controllers'], function () {
    Route::group(['middleware' => 'signed-url'], function () {
        Route::post('invoices/{invoice}/offlinepayment', 'OfflinePayment@link');
        Route::post('invoices/{invoice}/offlinepayment/confirm', 'OfflinePayment@confirm');
    });
});

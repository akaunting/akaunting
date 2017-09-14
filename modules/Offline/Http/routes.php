<?php

Route::group(['middleware' => ['web', 'auth', 'language', 'adminmenu', 'permission:read-admin-panel'], 'prefix' => 'modules/offline', 'namespace' => 'Modules\Offline\Http\Controllers'], function () {
    Route::get('settings', 'settings@edit');
    Route::post('settings', 'settings@update');
    Route::post('settings/get', 'settings@get');
    Route::post('settings/delete', 'settings@delete');
});

<?php

use Illuminate\Support\Facades\Route;

/**
 * 'common' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapCommonRoutes
 */

Route::group(['middleware' => 'auth'], function () {
    Route::group(['as' => 'uploads.', 'prefix' => 'uploads'], function () {
        Route::get('{id}', 'Common\Uploads@get')->name('get');
        Route::get('{id}/show', 'Common\Uploads@show')->name('show');
        Route::get('{id}/download', 'Common\Uploads@download')->name('download');
    });

    Route::group(['middleware' => ['permission:read-admin-panel']], function () {
        Route::group(['middleware' => ['menu.admin']], function () {
            Route::get('/', 'Common\Dashboards@show')->name('dashboard');
        });

        Route::get('wizard', 'Wizard\Companies@edit')->name('wizard.edit');
    });

    Route::group(['middleware' => ['menu.portal', 'permission:read-client-portal']], function () {
        Route::get('portal', 'Portal\Dashboard@index')->name('portal.dashboard');
    });
});

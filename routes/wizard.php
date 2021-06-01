<?php

use Illuminate\Support\Facades\Route;

/**
 * 'wizard' middleware and prefix applied to all routes
 *
 * @see \App\Providers\Route::mapWizardRoutes
 */

Route::group(['as' => 'wizard.'], function () {
    Route::get('companies', 'Wizard\Companies@edit')->name('companies.edit');
    Route::post('companies', 'Wizard\Companies@update')->middleware('dropzone')->name('companies.update');

    Route::get('currencies/{currency}/enable', 'Settings\Currencies@enable')->name('currencies.enable');
    Route::get('currencies/{currency}/disable', 'Settings\Currencies@disable')->name('currencies.disable');
    Route::resource('currencies', 'Wizard\Currencies');

    Route::get('taxes/{tax}/enable', 'Settings\Taxes@enable')->name('taxes.enable');
    Route::get('taxes/{tax}/disable', 'Settings\Taxes@disable')->name('taxes.disable');
    Route::resource('taxes', 'Wizard\Taxes');

    Route::get('finish', 'Wizard\Finish@index')->name('finish.index');
    Route::patch('finish', 'Wizard\Finish@update')->name('finish.update');
});

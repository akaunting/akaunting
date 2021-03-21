<?php

use Illuminate\Support\Facades\Route;

/**
 * 'wizard' middleware and prefix applied to all routes
 *
 * @see \App\Providers\Route::mapWizardRoutes
 */

Route::group(['as' => 'wizard.'], function () {
    Route::get('companies', 'Wizard\Companies@edit')->name('companies.edit');
    Route::patch('companies', 'Wizard\Companies@update')->name('companies.update');

    Route::resource('currencies', 'Wizard\Currencies');
    Route::resource('taxes', 'Wizard\Taxes');

    Route::get('finish', 'Wizard\Finish@index')->name('finish.index');
});

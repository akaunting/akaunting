<?php

// 'wizard' middleware applied via App\Providers\Route

Route::group(['as' => 'wizard.'], function () {
    Route::get('companies', 'Wizard\Companies@edit')->name('companies.edit');
    Route::patch('companies', 'Wizard\Companies@update')->name('companies.update');

    Route::resource('currencies', 'Wizard\Currencies');
    Route::resource('taxes', 'Wizard\Taxes');

    Route::get('finish', 'Wizard\Finish@index')->name('finish.index');
});

<?php

Route::group(['as' => 'wizard.'], function () {
    Route::get('companies', 'Wizard\Companies@edit')->name('companies.edit');
    Route::patch('companies', 'Wizard\Companies@update')->name('companies.update');

    Route::get('currencies', 'Wizard\Currencies@index')->name('currencies.index');
    Route::get('currencies/{currency}/delete', 'Wizard\Currencies@destroy')->name('currencies.delete');
    Route::post('currencies', 'Wizard\Currencies@store')->name('currencies.store');
    Route::patch('currencies/{currency}', 'Wizard\Currencies@update')->name('currencies.update');

    Route::get('taxes', 'Wizard\Taxes@index')->name('taxes.index');
    Route::get('taxes/{tax}/delete', 'Wizard\Taxes@destroy')->name('taxes.delete');
    Route::post('taxes', 'Wizard\Taxes@store')->name('taxes.store');
    Route::patch('taxes/{tax}', 'Wizard\Taxes@update')->name('taxes.update');

    Route::get('finish', 'Wizard\Finish@index')->name('finish.index');
});

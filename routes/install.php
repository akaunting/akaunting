<?php

use Illuminate\Support\Facades\Route;

/**
 * 'install' middleware and prefix applied to all routes
 *
 * @see \App\Providers\Route::mapInstallRoutes
 */

Route::get('/', 'Install\Requirements@show');
Route::get('requirements', 'Install\Requirements@show')->name('install.requirements');

Route::get('language', 'Install\Language@create')->name('install.language');
Route::get('language/getLanguages', 'Install\Language@getLanguages');
Route::post('language', 'Install\Language@store');

Route::get('database', 'Install\Database@create')->name('install.database');
Route::post('database', 'Install\Database@store');

Route::get('settings', 'Install\Settings@create')->name('install.settings');
Route::post('settings', 'Install\Settings@store');

<?php

if (!config('language.route')) {
    return;
}

Route::group([
    'middleware' => ['web', 'language'],
    'as'         => 'language::',
    'prefix'     => config('language.prefix'),
], function () {
    $controller = config('language.controller');

    Route::get('/{locale}/back', $controller . '@back')->name('back');

    if (config('language.home')) {
        Route::get('/{locale}/home', $controller . '@home')->name('home');
    }
});

<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
Route::get('/index-devsecops', function () {
    return view('index_custom');
});
Route::get('/register-custom', function () {
    return view('register_custom');
});

/**
 * 'web' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapWebRoutes
 */

 Livewire::setScriptRoute(function ($handle) {
    $base = request()->getBasePath();

    return Route::get($base . '/vendor/livewire/livewire/dist/livewire.min.js', $handle);
});
// Ajout de la page d'accueil personnalisée
Route::get('/', function () {
    return view('welcome_custom');
})->name('index');
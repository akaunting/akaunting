<?php

use Illuminate\Support\Facades\Route;

/**
 * 'guest' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapGuestRoutes
 * @see \modules\PaypalStandard\Routes\guest.php for module example
 */

/**
 * OAuth/MCP Discovery endpoints (public, no middleware)
 */

// OAuth 2.0 Authorization Server Metadata (RFC 8414).
// Must live at the ROOT /.well-known/ path so that clients following
// RFC 8414 discovery (e.g. ChatGPT) can reach it.  The same route also
// exists under /oauth/.well-known/... but that prefixed URL is NOT where
// clients look when the authorization_servers entry is the base URL.
Route::get('.well-known/oauth-authorization-server', 'OAuth\Discovery@metadata')
    ->name('oauth.metadata.root')
    ->withoutMiddleware('guest');

// ChatGPT AI Plugin Manifest
Route::get('.well-known/ai-plugin.json', 'OAuth\Discovery@aiPlugin')
    ->name('ai-plugin.manifest')
    ->withoutMiddleware('guest');

// MCP Manifest
Route::get('.well-known/mcp.json', 'OAuth\Discovery@mcpManifest')
    ->name('mcp.manifest')
    ->withoutMiddleware('guest');

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'Auth\Login@create')->name('login');
    Route::post('login', 'Auth\Login@store')->name('login.store');

    Route::get('forgot', 'Auth\Forgot@create')->name('forgot');
    Route::post('forgot', 'Auth\Forgot@store')->name('forgot.store');

    //Route::get('reset', 'Auth\Reset@create');
    Route::get('reset/{token}', 'Auth\Reset@create')->name('reset');
    Route::post('reset', 'Auth\Reset@store')->name('reset.store');

    Route::get('register/{token}', 'Auth\Register@create')->name('register');
    Route::post('register', 'Auth\Register@store')->name('register.store');
});

Route::get('/', function () {
    return redirect()->route('login');
});

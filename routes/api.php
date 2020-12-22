<?php

use Illuminate\Support\Facades\Route;

/**
 * 'api' prefix applied to all routes
 *
 * @see \App\Providers\Route::mapApiRoutes
 */

$api = app('Dingo\Api\Routing\Router');

$api->version('v2', ['middleware' => ['api']], function($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api'], function($api) {
        // Companies
        $api->get('companies/{company}/owner', 'Common\Companies@owner')->name('companies.owner');
        $api->get('companies/{company}/enable', 'Common\Companies@enable')->name('companies.enable');
        $api->get('companies/{company}/disable', 'Common\Companies@disable')->name('companies.disable');
        $api->resource('companies', 'Common\Companies');

        // Items
        $api->get('items/{item}/enable', 'Common\Items@enable')->name('items.enable');
        $api->get('items/{item}/disable', 'Common\Items@disable')->name('items.disable');
        $api->resource('items', 'Common\Items');

        // Contacts
        $api->get('contacts/{contact}/enable', 'Common\Contacts@enable')->name('contacts.enable');
        $api->get('contacts/{contact}/disable', 'Common\Contacts@disable')->name('contacts.disable');
        $api->resource('contacts', 'Common\Contacts');

        // Sales
        $api->resource('invoices', 'Sales\Invoices');
        $api->resource('invoices.transactions', 'Sales\InvoiceTransactions');

        // Purchases
        $api->get('bills/{bill}/received', 'Purchases\Bills@received')->name('bills.received');
        $api->resource('bills', 'Purchases\Bills');

        // Banking
        $api->get('accounts/{account}/enable', 'Banking\Accounts@enable')->name('accounts.enable');
        $api->get('accounts/{account}/disable', 'Banking\Accounts@disable')->name('accounts.disable');
        $api->resource('accounts', 'Banking\Accounts');
        $api->resource('reconciliations', 'Banking\Reconciliations');
        $api->resource('transactions', 'Banking\Transactions');
        $api->resource('transfers', 'Banking\Transfers');

        // Reports
        $api->resource('reports', 'Common\Reports');

        // Settings
        $api->get('categories/{category}/enable', 'Settings\Categories@enable')->name('categories.enable');
        $api->get('categories/{category}/disable', 'Settings\Categories@disable')->name('categories.disable');
        $api->resource('categories', 'Settings\Categories');
        $api->get('currencies/{currency}/enable', 'Settings\Currencies@enable')->name('currencies.enable');
        $api->get('currencies/{currency}/disable', 'Settings\Currencies@disable')->name('currencies.disable');
        $api->resource('currencies', 'Settings\Currencies');
        $api->resource('settings', 'Settings\Settings');
        $api->get('taxes/{tax}/enable', 'Settings\Taxes@enable')->name('taxes.enable');
        $api->get('taxes/{tax}/disable', 'Settings\Taxes@disable')->name('taxes.disable');
        $api->resource('taxes', 'Settings\Taxes');

        // Common
        $api->resource('ping', 'Common\Ping');

        // Auth
        $api->resource('permissions', 'Auth\Permissions');
        $api->resource('roles', 'Auth\Roles');
        $api->get('users/{user}/enable', 'Auth\Users@enable')->name('users.enable');
        $api->get('users/{user}/disable', 'Auth\Users@disable')->name('users.disable');
        $api->resource('users', 'Auth\Users');
    });
});

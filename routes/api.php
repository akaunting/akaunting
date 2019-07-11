<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => ['api']], function($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api'], function($api) {
        // Companies
        $api->resource('companies', 'Common\Companies');

        // Items
        $api->resource('items', 'Common\Items');

        // Incomes
        $api->resource('customers', 'Incomes\Customers');
        $api->resource('invoices', 'Incomes\Invoices');
        $api->resource('invoices.payments', 'Incomes\InvoicePayments');
        $api->resource('revenues', 'Incomes\Revenues');

        // Expenses
        $api->resource('bills', 'Expenses\Bills');
        $api->resource('payments', 'Expenses\Payments');
        $api->resource('vendors', 'Expenses\Vendors');

        // Banking
        $api->resource('accounts', 'Banking\Accounts');
        $api->resource('transfers', 'Banking\Transfers');

        // Settings
        $api->resource('categories', 'Settings\Categories');
        $api->resource('currencies', 'Settings\Currencies');
        $api->resource('settings', 'Settings\Settings');
        $api->resource('taxes', 'Settings\Taxes');

        // Common
        $api->resource('ping', 'Common\Ping');

        // Auth
        $api->resource('permissions', 'Auth\Permissions');
        $api->resource('roles', 'Auth\Roles');
        $api->resource('users', 'Auth\Users');
    });
});

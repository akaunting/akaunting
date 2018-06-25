<?php

Route::group(['middleware' => 'language'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['prefix' => 'uploads'], function () {
            Route::get('{id}', 'Common\Uploads@get');
            Route::get('{id}/download', 'Common\Uploads@download');
        });

        Route::group(['middleware' => ['adminmenu', 'permission:read-admin-panel']], function () {
            Route::get('/', 'Common\Dashboard@index');

            Route::group(['prefix' => 'uploads'], function () {
                Route::delete('{id}', 'Common\Uploads@destroy');
            });

            Route::group(['prefix' => 'common'], function () {
                Route::get('companies/{company}/set', 'Common\Companies@set')->name('companies.switch');
                Route::get('companies/{company}/enable', 'Common\Companies@enable')->name('companies.enable');
                Route::get('companies/{company}/disable', 'Common\Companies@disable')->name('companies.disable');
                Route::resource('companies', 'Common\Companies');
                Route::get('dashboard/cashflow', 'Common\Dashboard@cashFlow')->name('dashboard.cashflow');
                Route::get('import/{group}/{type}', 'Common\Import@create')->name('import.create');
                Route::get('items/autocomplete', 'Common\Items@autocomplete')->name('items.autocomplete');
                Route::post('items/totalItem', 'Common\Items@totalItem')->name('items.total');
                Route::get('items/{item}/duplicate', 'Common\Items@duplicate')->name('items.duplicate');
                Route::post('items/import', 'Common\Items@import')->name('items.import');
                Route::get('items/export', 'Common\Items@export')->name('items.export');
                Route::get('items/{item}/enable', 'Common\Items@enable')->name('items.enable');
                Route::get('items/{item}/disable', 'Common\Items@disable')->name('items.disable');
                Route::resource('items', 'Common\Items');
                Route::get('search/search', 'Common\Search@search')->name('search.search');
                Route::resource('search', 'Common\Search');
            });

            Route::group(['prefix' => 'auth'], function () {
                Route::get('logout', 'Auth\Login@destroy')->name('logout');
                Route::get('users/autocomplete', 'Auth\Users@autocomplete');
                Route::get('users/{user}/read-bills', 'Auth\Users@readUpcomingBills');
                Route::get('users/{user}/read-invoices', 'Auth\Users@readOverdueInvoices');
                Route::get('users/{user}/read-items', 'Auth\Users@readItemsOutOfStock');
                Route::get('users/{user}/enable', 'Auth\Users@enable')->name('users.enable');
                Route::get('users/{user}/disable', 'Auth\Users@disable')->name('users.disable');
                Route::resource('users', 'Auth\Users');
                Route::resource('roles', 'Auth\Roles');
                Route::resource('permissions', 'Auth\Permissions');
            });

            Route::group(['prefix' => 'incomes'], function () {
                Route::get('invoices/{invoice}/sent', 'Incomes\Invoices@markSent');
                Route::get('invoices/{invoice}/email', 'Incomes\Invoices@emailInvoice');
                Route::get('invoices/{invoice}/pay', 'Incomes\Invoices@markPaid');
                Route::get('invoices/{invoice}/print', 'Incomes\Invoices@printInvoice');
                Route::get('invoices/{invoice}/pdf', 'Incomes\Invoices@pdfInvoice');
                Route::get('invoices/{invoice}/duplicate', 'Incomes\Invoices@duplicate');
                Route::post('invoices/payment', 'Incomes\Invoices@payment');
                Route::delete('invoices/payment/{payment}', 'Incomes\Invoices@paymentDestroy');
                Route::post('invoices/import', 'Incomes\Invoices@import')->name('invoices.import');
                Route::get('invoices/export', 'Incomes\Invoices@export')->name('invoices.export');
                Route::resource('invoices', 'Incomes\Invoices');
                Route::get('revenues/{revenue}/duplicate', 'Incomes\Revenues@duplicate');
                Route::post('revenues/import', 'Incomes\Revenues@import')->name('revenues.import');
                Route::get('revenues/export', 'Incomes\Revenues@export')->name('revenues.export');
                Route::resource('revenues', 'Incomes\Revenues');
                Route::get('customers/currency', 'Incomes\Customers@currency');
                Route::get('customers/{customer}/duplicate', 'Incomes\Customers@duplicate');
                Route::post('customers/customer', 'Incomes\Customers@customer');
                Route::post('customers/field', 'Incomes\Customers@field');
                Route::post('customers/import', 'Incomes\Customers@import')->name('customers.import');
                Route::get('customers/export', 'Incomes\Customers@export')->name('customers.export');
                Route::get('customers/{customer}/enable', 'Incomes\Customers@enable')->name('customers.enable');
                Route::get('customers/{customer}/disable', 'Incomes\Customers@disable')->name('customers.disable');
                Route::resource('customers', 'Incomes\Customers');
            });

            Route::group(['prefix' => 'expenses'], function () {
                Route::get('bills/{bill}/received', 'Expenses\Bills@markReceived');
                Route::get('bills/{bill}/print', 'Expenses\Bills@printBill');
                Route::get('bills/{bill}/pdf', 'Expenses\Bills@pdfBill');
                Route::get('bills/{bill}/duplicate', 'Expenses\Bills@duplicate');
                Route::post('bills/payment', 'Expenses\Bills@payment');
                Route::delete('bills/payment/{payment}', 'Expenses\Bills@paymentDestroy');
                Route::post('bills/import', 'Expenses\Bills@import')->name('bills.import');
                Route::get('bills/export', 'Expenses\Bills@export')->name('bills.export');
                Route::resource('bills', 'Expenses\Bills');
                Route::get('payments/{payment}/duplicate', 'Expenses\Payments@duplicate');
                Route::post('payments/import', 'Expenses\Payments@import')->name('payments.import');
                Route::get('payments/export', 'Expenses\Payments@export')->name('payments.export');
                Route::resource('payments', 'Expenses\Payments');
                Route::get('vendors/currency', 'Expenses\Vendors@currency');
                Route::get('vendors/{vendor}/duplicate', 'Expenses\Vendors@duplicate');
                Route::post('vendors/vendor', 'Expenses\Vendors@vendor');
                Route::post('vendors/import', 'Expenses\Vendors@import')->name('vendors.import');
                Route::get('vendors/export', 'Expenses\Vendors@export')->name('vendors.export');
                Route::get('vendors/{vendor}/enable', 'Expenses\Vendors@enable')->name('vendors.enable');
                Route::get('vendors/{vendor}/disable', 'Expenses\Vendors@disable')->name('vendors.disable');
                Route::resource('vendors', 'Expenses\Vendors');
            });

            Route::group(['prefix' => 'banking'], function () {
                Route::get('accounts/currency', 'Banking\Accounts@currency')->name('accounts.currency');
                Route::get('accounts/{account}/enable', 'Banking\Accounts@enable')->name('accounts.enable');
                Route::get('accounts/{account}/disable', 'Banking\Accounts@disable')->name('accounts.disable');
                Route::resource('accounts', 'Banking\Accounts');
                Route::resource('transactions', 'Banking\Transactions');
                Route::resource('transfers', 'Banking\Transfers');
            });

            Route::group(['prefix' => 'reports'], function () {
                Route::resource('income-summary', 'Reports\IncomeSummary');
                Route::resource('expense-summary', 'Reports\ExpenseSummary');
                Route::resource('income-expense-summary', 'Reports\IncomeExpenseSummary');
                Route::resource('tax-summary', 'Reports\TaxSummary');
                Route::resource('profit-loss', 'Reports\ProfitLoss');
            });

            Route::group(['prefix' => 'settings'], function () {
                Route::post('categories/category', 'Settings\Categories@category');
                Route::get('categories/{category}/enable', 'Settings\Categories@enable')->name('categories.enable');
                Route::get('categories/{category}/disable', 'Settings\Categories@disable')->name('categories.disable');
                Route::resource('categories', 'Settings\Categories');
                Route::get('currencies/currency', 'Settings\Currencies@currency');
                Route::get('currencies/config', 'Settings\Currencies@config');
                Route::get('currencies/{currency}/enable', 'Settings\Currencies@enable')->name('currencies.enable');
                Route::get('currencies/{currency}/disable', 'Settings\Currencies@disable')->name('currencies.disable');
                Route::resource('currencies', 'Settings\Currencies');
                Route::get('settings', 'Settings\Settings@edit');
                Route::patch('settings', 'Settings\Settings@update');
                Route::get('taxes/{tax}/enable', 'Settings\Taxes@enable')->name('taxes.enable');
                Route::get('taxes/{tax}/disable', 'Settings\Taxes@disable')->name('taxes.disable');
                Route::resource('taxes', 'Settings\Taxes');
                Route::get('apps/{alias}', 'Settings\Modules@edit');
                Route::patch('apps/{alias}', 'Settings\Modules@update');
            });

            Route::group(['prefix' => 'apps'], function () {
                Route::resource('token', 'Modules\Token');
                Route::resource('home', 'Modules\Home');
                Route::resource('my', 'Modules\My');
                Route::get('categories/{alias}', 'Modules\Tiles@categoryModules');
                Route::get('paid', 'Modules\Tiles@paidModules');
                Route::get('new', 'Modules\Tiles@newModules');
                Route::get('free', 'Modules\Tiles@freeModules');
                Route::get('search', 'Modules\Tiles@searchModules');
                Route::post('steps', 'Modules\Item@steps');
                Route::post('download', 'Modules\Item@download');
                Route::post('unzip', 'Modules\Item@unzip');
                Route::post('install', 'Modules\Item@install');
                Route::get('post/{alias}', 'Modules\Item@post');
                Route::get('{alias}/uninstall', 'Modules\Item@uninstall');
                Route::get('{alias}/enable', 'Modules\Item@enable');
                Route::get('{alias}/disable', 'Modules\Item@disable');
                Route::get('{alias}', 'Modules\Item@show');
            });

            Route::group(['prefix' => 'install'], function () {
                Route::get('updates/changelog', 'Install\Updates@changelog');
                Route::get('updates/check', 'Install\Updates@check');
                Route::get('updates/update/{alias}/{version}', 'Install\Updates@update');
                Route::get('updates/post/{alias}/{old}/{new}', 'Install\Updates@post');
                Route::resource('updates', 'Install\Updates');
            });

            /* @deprecated */
            Route::post('items/items/totalItem', 'Common\Items@totalItem');
        });

        Route::group(['middleware' => ['customermenu', 'permission:read-customer-panel']], function () {
            Route::group(['prefix' => 'customers'], function () {
                Route::get('/', 'Customers\Dashboard@index');

                Route::get('invoices/{invoice}/print', 'Customers\Invoices@printInvoice');
                Route::get('invoices/{invoice}/pdf', 'Customers\Invoices@pdfInvoice');
                Route::post('invoices/{invoice}/payment', 'Customers\Invoices@payment');
                Route::post('invoices/{invoice}/confirm', 'Customers\Invoices@confirm');
                Route::resource('invoices', 'Customers\Invoices');
                Route::resource('payments', 'Customers\Payments');
                Route::resource('transactions', 'Customers\Transactions');
                Route::get('profile/read-invoices', 'Customers\Profile@readOverdueInvoices');
                Route::resource('profile', 'Customers\Profile');

                Route::get('logout', 'Auth\Login@destroy')->name('customer_logout');
            });
        });
    });

    Route::group(['middleware' => 'guest'], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::get('login', 'Auth\Login@create')->name('login');
            Route::post('login', 'Auth\Login@store');

            Route::get('forgot', 'Auth\Forgot@create')->name('forgot');
            Route::post('forgot', 'Auth\Forgot@store');

            //Route::get('reset', 'Auth\Reset@create');
            Route::get('reset/{token}', 'Auth\Reset@create')->name('reset');
            Route::post('reset', 'Auth\Reset@store');
        });

        Route::group(['middleware' => 'install'], function () {
            Route::group(['prefix' => 'install'], function () {
                Route::get('/', 'Install\Requirements@show');
                Route::get('requirements', 'Install\Requirements@show');

                Route::get('language', 'Install\Language@create');
                Route::post('language', 'Install\Language@store');

                Route::get('database', 'Install\Database@create');
                Route::post('database', 'Install\Database@store');

                Route::get('settings', 'Install\Settings@create');
                Route::post('settings', 'Install\Settings@store');
            });
        });
    });
});

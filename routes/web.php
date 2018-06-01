<?php

Route::group(['middleware' => 'language'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['prefix' => 'uploads'], function () {
            Route::get('{id}', 'Common\Uploads@get');
            Route::get('{id}/download', 'Common\Uploads@download');
        });

        Route::group(['middleware' => ['adminmenu', 'permission:read-admin-panel']], function () {
            Route::get('/', 'Dashboard\Dashboard@index');
            Route::get('dashboard/dashboard/cashflow', 'Dashboard\Dashboard@cashFlow');

            Route::group(['prefix' => 'uploads'], function () {
                Route::delete('{id}', 'Common\Uploads@destroy');
            });

            Route::group(['prefix' => 'search'], function () {
                Route::get('search/search', 'Search\Search@search');
                Route::resource('search', 'Search\Search');
            });

            Route::group(['prefix' => 'common'], function () {
                Route::get('import/{group}/{type}', 'Common\Import@create');
            });

            Route::group(['prefix' => 'items'], function () {
                Route::get('items/autocomplete', 'Items\Items@autocomplete');
                Route::post('items/totalItem', 'Items\Items@totalItem');
                Route::get('items/{item}/duplicate', 'Items\Items@duplicate');
                Route::post('items/import', 'Items\Items@import');
                Route::resource('items', 'Items\Items');
            });

            Route::group(['prefix' => 'auth'], function () {
                Route::get('logout', 'Auth\Login@destroy')->name('logout');
                Route::get('users/autocomplete', 'Auth\Users@autocomplete');
                Route::get('users/{user}/read-bills', 'Auth\Users@readUpcomingBills');
                Route::get('users/{user}/read-invoices', 'Auth\Users@readOverdueInvoices');
                Route::get('users/{user}/read-items', 'Auth\Users@readItemsOutOfStock');
                Route::resource('users', 'Auth\Users');
                Route::resource('roles', 'Auth\Roles');
                Route::resource('permissions', 'Auth\Permissions');
            });

            Route::group(['prefix' => 'companies'], function () {
                Route::get('companies/{company}/set', 'Companies\Companies@set');
                Route::resource('companies', 'Companies\Companies');
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
                Route::post('invoices/import', 'Incomes\Invoices@import');
                Route::resource('invoices', 'Incomes\Invoices');
                Route::get('revenues/{revenue}/duplicate', 'Incomes\Revenues@duplicate');
                Route::post('revenues/import', 'Incomes\Revenues@import');
                Route::resource('revenues', 'Incomes\Revenues');
                Route::get('customers/currency', 'Incomes\Customers@currency');
                Route::get('customers/{customer}/duplicate', 'Incomes\Customers@duplicate');
                Route::post('customers/customer', 'Incomes\Customers@customer');
                Route::post('customers/field', 'Incomes\Customers@field');
                Route::post('customers/import', 'Incomes\Customers@import');
                Route::resource('customers', 'Incomes\Customers');
            });

            Route::group(['prefix' => 'expenses'], function () {
                Route::get('bills/{bill}/received', 'Expenses\Bills@markReceived');
                Route::get('bills/{bill}/print', 'Expenses\Bills@printBill');
                Route::get('bills/{bill}/pdf', 'Expenses\Bills@pdfBill');
                Route::get('bills/{bill}/duplicate', 'Expenses\Bills@duplicate');
                Route::post('bills/payment', 'Expenses\Bills@payment');
                Route::delete('bills/payment/{payment}', 'Expenses\Bills@paymentDestroy');
                Route::post('bills/import', 'Expenses\Bills@import');
                Route::resource('bills', 'Expenses\Bills');
                Route::get('payments/{payment}/duplicate', 'Expenses\Payments@duplicate');
                Route::post('payments/import', 'Expenses\Payments@import');
                Route::resource('payments', 'Expenses\Payments');
                Route::get('vendors/currency', 'Expenses\Vendors@currency');
                Route::get('vendors/{vendor}/duplicate', 'Expenses\Vendors@duplicate');
                Route::post('vendors/vendor', 'Expenses\Vendors@vendor');
                Route::post('vendors/import', 'Expenses\Vendors@import');
                Route::resource('vendors', 'Expenses\Vendors');
            });

            Route::group(['prefix' => 'banking'], function () {
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
                Route::resource('categories', 'Settings\Categories');
                Route::get('currencies/currency', 'Settings\Currencies@currency');
                Route::get('currencies/config', 'Settings\Currencies@config');
                Route::resource('currencies', 'Settings\Currencies');
                Route::get('settings', 'Settings\Settings@edit');
                Route::patch('settings', 'Settings\Settings@update');
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

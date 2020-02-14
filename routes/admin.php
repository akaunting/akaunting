<?php

/**
 * 'admin' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapAdminRoutes
 * @see \modules\OfflinePayments\Routes\admin.php for module example
 */

Route::group(['as' => 'uploads.', 'prefix' => 'uploads'], function () {
    Route::delete('{id}', 'Common\Uploads@destroy')->name('destroy');
});

Route::group(['prefix' => 'common'], function () {
    Route::get('companies/{company}/switch', 'Common\Companies@switch')->name('companies.switch');
    Route::get('companies/{company}/enable', 'Common\Companies@enable')->name('companies.enable');
    Route::get('companies/{company}/disable', 'Common\Companies@disable')->name('companies.disable');
    Route::resource('companies', 'Common\Companies');

    Route::get('dashboards/{dashboard}/switch', 'Common\Dashboards@switch')->name('dashboards.switch');
    Route::get('dashboards/{dashboard}/enable', 'Common\Dashboards@enable')->name('dashboards.enable');
    Route::get('dashboards/{dashboard}/disable', 'Common\Dashboards@disable')->name('dashboards.disable');
    Route::resource('dashboards', 'Common\Dashboards');

    Route::post('widgets/getData', 'Common\Widgets@getData')->name('widgets.getData');
    Route::resource('widgets', 'Common\Widgets');

    Route::get('import/{group}/{type}', 'Common\Import@create')->name('import.create');

    Route::get('items/autocomplete', 'Common\Items@autocomplete')->name('items.autocomplete');
    Route::post('items/total', 'Common\Items@total')->middleware(['money'])->name('items.total');
    Route::get('items/{item}/duplicate', 'Common\Items@duplicate')->name('items.duplicate');
    Route::post('items/import', 'Common\Items@import')->name('items.import');
    Route::get('items/export', 'Common\Items@export')->name('items.export');
    Route::get('items/{item}/enable', 'Common\Items@enable')->name('items.enable');
    Route::get('items/{item}/disable', 'Common\Items@disable')->name('items.disable');
    Route::resource('items', 'Common\Items', ['middleware' => ['money']]);

    Route::resource('search', 'Common\Search');

    Route::post('notifications/disable', 'Common\Notifications@disable')->name('notifications.disable');

    Route::post('bulk-actions/{group}/{type}', 'Common\BulkActions@action')->name('bulk-actions.action');

    Route::get('reports/{report}/print', 'Common\Reports@print')->name('reports.print');
    Route::get('reports/{report}/export', 'Common\Reports@export')->name('reports.export');
    Route::get('reports/{report}/duplicate', 'Common\Reports@duplicate')->name('reports.duplicate');
    Route::get('reports/clear', 'Common\Reports@clear')->name('reports.clear');
    Route::get('reports/fields', 'Common\Reports@fields')->name('reports.fields');
    Route::resource('reports', 'Common\Reports');
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('logout', 'Auth\Login@destroy')->name('logout');

    Route::get('users/autocomplete', 'Auth\Users@autocomplete')->name('users.autocomplete');
    Route::get('users/{user}/read-bills', 'Auth\Users@readUpcomingBills')->name('users.read.bills');
    Route::get('users/{user}/read-invoices', 'Auth\Users@readOverdueInvoices')->name('users.read.invoices');
    Route::get('users/{user}/enable', 'Auth\Users@enable')->name('users.enable');
    Route::get('users/{user}/disable', 'Auth\Users@disable')->name('users.disable');
    Route::resource('users', 'Auth\Users');

    Route::resource('roles', 'Auth\Roles');

    Route::resource('permissions', 'Auth\Permissions');
});

Route::group(['prefix' => 'sales'], function () {
    Route::get('invoices/{invoice}/sent', 'Sales\Invoices@markSent')->name('invoices.sent');
    Route::get('invoices/{invoice}/email', 'Sales\Invoices@emailInvoice')->name('invoices.email');
    Route::get('invoices/{invoice}/pay', 'Sales\Invoices@markPaid')->name('invoices.paid');
    Route::get('invoices/{invoice}/print', 'Sales\Invoices@printInvoice')->name('invoices.print');
    Route::get('invoices/{invoice}/pdf', 'Sales\Invoices@pdfInvoice')->name('invoices.pdf');
    Route::get('invoices/{invoice}/duplicate', 'Sales\Invoices@duplicate')->name('invoices.duplicate');
    Route::get('invoices/addItem', 'Sales\Invoices@addItem')->middleware(['money'])->name('invoice.add.item');
    Route::post('invoices/import', 'Sales\Invoices@import')->name('invoices.import');
    Route::get('invoices/export', 'Sales\Invoices@export')->name('invoices.export');
    Route::resource('invoices', 'Sales\Invoices', ['middleware' => ['date.format', 'money']]);

    Route::get('revenues/{revenue}/duplicate', 'Sales\Revenues@duplicate')->name('revenues.duplicate');
    Route::post('revenues/import', 'Sales\Revenues@import')->name('revenues.import');
    Route::get('revenues/export', 'Sales\Revenues@export')->name('revenues.export');
    Route::resource('revenues', 'Sales\Revenues', ['middleware' => ['date.format', 'money']]);

    Route::get('customers/currency', 'Sales\Customers@currency');
    Route::get('customers/{customer}/duplicate', 'Sales\Customers@duplicate')->name('customers.duplicate');
    Route::post('customers/field', 'Sales\Customers@field')->name('customers.field');
    Route::post('customers/import', 'Sales\Customers@import')->name('customers.import');
    Route::get('customers/export', 'Sales\Customers@export')->name('customers.export');
    Route::get('customers/{customer}/enable', 'Sales\Customers@enable')->name('customers.enable');
    Route::get('customers/{customer}/disable', 'Sales\Customers@disable')->name('customers.disable');
    Route::get('customers/{customer}/currency', 'Sales\Customers@currency')->name('customers.currency');
    Route::resource('customers', 'Sales\Customers');
});

Route::group(['prefix' => 'purchases'], function () {
    Route::get('bills/{bill}/received', 'Purchases\Bills@markReceived')->name('bills.received');
    Route::get('bills/{bill}/print', 'Purchases\Bills@printBill')->name('bills.print');
    Route::get('bills/{bill}/pdf', 'Purchases\Bills@pdfBill')->name('bills.pdf');
    Route::get('bills/{bill}/duplicate', 'Purchases\Bills@duplicate')->name('bills.duplicate');
    Route::get('bills/addItem', 'Purchases\Bills@addItem')->middleware(['money'])->name('bill.add.item');
    Route::post('bills/import', 'Purchases\Bills@import')->name('bills.import');
    Route::get('bills/export', 'Purchases\Bills@export')->name('bills.export');
    Route::resource('bills', 'Purchases\Bills', ['middleware' => ['date.format', 'money']]);

    Route::get('payments/{payment}/duplicate', 'Purchases\Payments@duplicate')->name('payments.duplicate');
    Route::post('payments/import', 'Purchases\Payments@import')->name('payments.import');
    Route::get('payments/export', 'Purchases\Payments@export')->name('payments.export');
    Route::resource('payments', 'Purchases\Payments', ['middleware' => ['date.format', 'money']]);

    Route::get('vendors/currency', 'Purchases\Vendors@currency');
    Route::get('vendors/{vendor}/duplicate', 'Purchases\Vendors@duplicate')->name('vendors.duplicate');
    Route::post('vendors/import', 'Purchases\Vendors@import')->name('vendors.import');
    Route::get('vendors/export', 'Purchases\Vendors@export')->name('vendors.export');
    Route::get('vendors/{vendor}/enable', 'Purchases\Vendors@enable')->name('vendors.enable');
    Route::get('vendors/{vendor}/currency', 'Purchases\Vendors@currency')->name('vendors.currency');
    Route::get('vendors/{vendor}/disable', 'Purchases\Vendors@disable')->name('vendors.disable');
    Route::resource('vendors', 'Purchases\Vendors');
});

Route::group(['prefix' => 'banking'], function () {
    Route::get('accounts/currency', 'Banking\Accounts@currency')->name('accounts.currency');
    Route::get('accounts/{account}/enable', 'Banking\Accounts@enable')->name('accounts.enable');
    Route::get('accounts/{account}/disable', 'Banking\Accounts@disable')->name('accounts.disable');
    Route::resource('accounts', 'Banking\Accounts', ['middleware' => ['date.format', 'money']]);

    Route::post('transactions/import', 'Banking\Transactions@import')->name('transactions.import');
    Route::get('transactions/export', 'Banking\Transactions@export')->name('transactions.export');
    Route::resource('transactions', 'Banking\Transactions');

    Route::resource('transfers', 'Banking\Transfers', ['middleware' => ['date.format', 'money']]);

    Route::post('reconciliations/calculate', 'Banking\Reconciliations@calculate')->middleware(['money']);
    Route::patch('reconciliations/calculate', 'Banking\Reconciliations@calculate')->middleware(['money']);
    Route::resource('reconciliations', 'Banking\Reconciliations', ['middleware' => ['date.format', 'money']]);
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

    Route::get('taxes/{tax}/enable', 'Settings\Taxes@enable')->name('taxes.enable');
    Route::get('taxes/{tax}/disable', 'Settings\Taxes@disable')->name('taxes.disable');
    Route::resource('taxes', 'Settings\Taxes');

    Route::group(['as' => 'settings.'], function () {
        Route::get('settings', 'Settings\Settings@index')->name('index');
        Route::patch('settings', 'Settings\Settings@update')->name('update');
        Route::get('company', 'Settings\Company@edit')->name('company.edit');
        Route::get('localisation', 'Settings\Localisation@edit')->name('localisation.edit');
        Route::get('invoice', 'Settings\Invoice@edit')->name('invoice.edit');
        Route::get('default', 'Settings\Defaults@edit')->name('default.edit');
        Route::get('email', 'Settings\Email@edit')->name('email.edit');
        Route::patch('email', 'Settings\Email@update')->name('email.update');
        Route::get('schedule', 'Settings\Schedule@edit')->name('schedule.edit');
    });
});

Route::group(['as' => 'settings.'], function () {
    Route::get('{alias}/settings', 'Settings\Modules@edit')->name('module.edit');
    Route::patch('{alias}/settings', 'Settings\Modules@update')->name('module.update');
});

Route::group(['as' => 'apps.', 'prefix' => 'apps'], function () {
    Route::resource('api-key', 'Modules\ApiKey');

    Route::group(['middleware' => 'api.key'], function () {
        Route::resource('home', 'Modules\Home');

        Route::resource('my', 'Modules\My');

        Route::get('categories/{alias}', 'Modules\Tiles@categoryModules')->name('categories.show');
        Route::get('vendors/{alias}', 'Modules\Tiles@vendorModules')->name('vendors.show');
        Route::get('docs/{alias}', 'Modules\Item@documentation')->name('docs.show');

        Route::get('paid', 'Modules\Tiles@paidModules')->name('paid');
        Route::get('new', 'Modules\Tiles@newModules')->name('new');
        Route::get('free', 'Modules\Tiles@freeModules')->name('free');
        Route::get('search', 'Modules\Tiles@searchModules')->name('search');
        Route::post('steps', 'Modules\Item@steps')->name('steps');
        Route::post('download', 'Modules\Item@download')->name('download');
        Route::post('unzip', 'Modules\Item@unzip')->name('unzip');
        Route::post('install', 'Modules\Item@install')->name('install');

        Route::post('{alias}/reviews', 'Modules\Item@reviews')->name('app.reviews');
        Route::get('{alias}/uninstall', 'Modules\Item@uninstall')->name('app.uninstall');
        Route::get('{alias}/enable', 'Modules\Item@enable')->name('app.enable');
        Route::get('{alias}/disable', 'Modules\Item@disable')->name('app.disable');
        Route::get('{alias}', 'Modules\Item@show')->name('app.show');
    });
});

Route::group(['prefix' => 'install'], function () {
    Route::get('updates/changelog', 'Install\Updates@changelog')->name('updates.changelog');
    Route::get('updates/check', 'Install\Updates@check')->name('updates.check');
    Route::get('updates/update/{alias}/{version}', 'Install\Updates@update')->name('updates.update');
    Route::post('updates/steps', 'Install\Updates@steps')->name('updates.steps');
    Route::post('updates/download', 'Install\Updates@download')->middleware('api.key')->name('updates.download');
    Route::post('updates/unzip', 'Install\Updates@unzip')->middleware('api.key')->name('updates.unzip');
    Route::post('updates/copy-files', 'Install\Updates@copyFiles')->middleware('api.key')->name('updates.copy');
    Route::post('updates/migrate', 'Install\Updates@migrate')->name('updates.migrate');
    Route::post('updates/finish', 'Install\Updates@finish')->name('updates.finish');
    Route::post('updates/redirect', 'Install\Updates@redirect')->name('updates.redirect');
    Route::resource('updates', 'Install\Updates');
});

Route::group(['as' => 'modals.', 'prefix' => 'modals'], function () {
    Route::resource('categories', 'Modals\Categories');
    Route::resource('currencies', 'Modals\Currencies');
    Route::resource('customers', 'Modals\Customers');
    Route::resource('vendors', 'Modals\Vendors');
    Route::resource('items', 'Modals\Items');
    Route::patch('invoice-templates', 'Modals\InvoiceTemplates@update')->name('invoice-templates.update');
    Route::resource('invoices/{invoice}/transactions', 'Modals\InvoiceTransactions', ['names' => 'invoices.invoice.transactions', 'middleware' => ['date.format', 'money']]);
    Route::resource('bills/{bill}/transactions', 'Modals\BillTransactions', ['names' => 'bills.bill.transactions', 'middleware' => ['date.format', 'money']]);
    Route::resource('taxes', 'Modals\Taxes');
});

<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapAdminRoutes
 * @see \modules\OfflinePayments\Routes\admin.php for module example
 */

Route::group(['as' => 'uploads.', 'prefix' => 'uploads'], function () {
    Route::get('{id}/inline', 'Common\Uploads@inline')->name('inline');
    Route::delete('{id}', 'Common\Uploads@destroy')->name('destroy');
});

Route::group(['prefix' => 'common'], function () {
    Route::get('companies/autocomplete', 'Common\Companies@autocomplete')->name('companies.autocomplete');
    Route::get('companies/{company}/switch', 'Common\Companies@switch')->name('companies.switch');
    Route::get('companies/{company}/enable', 'Common\Companies@enable')->name('companies.enable');
    Route::get('companies/{company}/disable', 'Common\Companies@disable')->name('companies.disable');
    Route::resource('companies', 'Common\Companies', ['middleware' => ['dropzone']]);

    Route::get('dashboards/{dashboard}/switch', 'Common\Dashboards@switch')->name('dashboards.switch');
    Route::get('dashboards/{dashboard}/enable', 'Common\Dashboards@enable')->name('dashboards.enable');
    Route::get('dashboards/{dashboard}/disable', 'Common\Dashboards@disable')->name('dashboards.disable');
    Route::resource('dashboards', 'Common\Dashboards');

    Route::post('widgets/getData', 'Common\Widgets@getData')->name('widgets.getData');
    Route::resource('widgets', 'Common\Widgets');

    Route::get('import/{group}/{type}/{route?}', 'Common\Import@create')->name('import.create');

    Route::get('items/autocomplete', 'Common\Items@autocomplete')->name('items.autocomplete');
    Route::get('items/{item}/enable', 'Common\Items@enable')->name('items.enable');
    Route::get('items/{item}/disable', 'Common\Items@disable')->name('items.disable');
    Route::get('items/{item}/duplicate', 'Common\Items@duplicate')->name('items.duplicate');
    Route::post('items/import', 'Common\Items@import')->middleware('import')->name('items.import');
    Route::get('items/export', 'Common\Items@export')->name('items.export');
    Route::resource('items', 'Common\Items', ['middleware' => ['money', 'dropzone']]);

    Route::post('bulk-actions/{group}/{type}', 'Common\BulkActions@action')->name('bulk-actions.action');

    Route::get('reports/{report}/print', 'Common\Reports@print')->name('reports.print');
    Route::get('reports/{report}/pdf', 'Common\Reports@pdf')->name('reports.pdf');
    Route::get('reports/{report}/export', 'Common\Reports@export')->name('reports.export');
    Route::get('reports/{report}/duplicate', 'Common\Reports@duplicate')->name('reports.duplicate');
    Route::get('reports/{report}/clear', 'Common\Reports@clear')->name('reports.clear');
    Route::get('reports/fields', 'Common\Reports@fields')->name('reports.fields');
    Route::resource('reports', 'Common\Reports');

    Route::get('contacts/index', 'Common\Contacts@index')->name('contacts.index');

    Route::get('plans/check', 'Common\Plans@check')->name('plans.check');
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('logout', 'Auth\Login@destroy')->name('logout');

    Route::get('users/autocomplete', 'Auth\Users@autocomplete')->name('users.autocomplete');
    Route::get('users/landingpages', 'Auth\Users@landingPages')->name('users.landingpages');
    Route::get('users/{user}/read-bills', 'Auth\Users@readUpcomingBills')->name('users.read.bills');
    Route::get('users/{user}/read-invoices', 'Auth\Users@readOverdueInvoices')->name('users.read.invoices');
    Route::get('users/{user}/enable', 'Auth\Users@enable')->name('users.enable');
    Route::get('users/{user}/disable', 'Auth\Users@disable')->name('users.disable');
    Route::get('users/{user}/invite', 'Auth\Users@invite')->name('users.invite');
    Route::resource('users', 'Auth\Users', ['middleware' => ['dropzone']]);

    Route::get('profile/{user}/edit', 'Auth\Users@edit')->name('profile.edit');
    Route::patch('profile/{user}', 'Auth\Users@update')->middleware('dropzone')->name('profile.update');
});

Route::group(['prefix' => 'sales'], function () {
    Route::get('invoices/{invoice}/sent', 'Sales\Invoices@markSent')->name('invoices.sent');
    Route::get('invoices/{invoice}/cancelled', 'Sales\Invoices@markCancelled')->name('invoices.cancelled');
    Route::get('invoices/{invoice}/restore', 'Sales\Invoices@restoreInvoice')->name('invoices.restore');
    Route::get('invoices/{invoice}/email', 'Sales\Invoices@emailInvoice')->name('invoices.email');
    Route::get('invoices/{invoice}/print', 'Sales\Invoices@printInvoice')->name('invoices.print');
    Route::get('invoices/{invoice}/pdf', 'Sales\Invoices@pdfInvoice')->name('invoices.pdf');
    Route::get('invoices/{invoice}/duplicate', 'Sales\Invoices@duplicate')->name('invoices.duplicate');
    Route::post('invoices/import', 'Sales\Invoices@import')->middleware('import')->name('invoices.import');
    Route::get('invoices/export', 'Sales\Invoices@export')->name('invoices.export');
    Route::resource('invoices', 'Sales\Invoices', ['middleware' => ['date.format', 'money', 'dropzone']]);

    Route::get('recurring-invoices/{recurring_invoice}/duplicate', 'Sales\RecurringInvoices@duplicate')->name('recurring-invoices.duplicate');
    Route::get('recurring-invoices/{recurring_invoice}/end', 'Sales\RecurringInvoices@end')->name('recurring-invoices.end');
    Route::post('recurring-invoices/import', 'Sales\RecurringInvoices@import')->middleware('import')->name('recurring-invoices.import');
    Route::get('recurring-invoices/export', 'Sales\RecurringInvoices@export')->name('recurring-invoices.export');
    Route::resource('recurring-invoices', 'Sales\RecurringInvoices', ['middleware' => ['date.format', 'money', 'dropzone']]);

    Route::get('customers/{customer}/create-invoice', 'Sales\Customers@createInvoice')->name('customers.create-invoice');
    Route::get('customers/{customer}/create-income', 'Sales\Customers@createIncome')->name('customers.create-income');
    Route::get('customers/{customer}/enable', 'Sales\Customers@enable')->name('customers.enable');
    Route::get('customers/{customer}/disable', 'Sales\Customers@disable')->name('customers.disable');
    Route::get('customers/{customer}/duplicate', 'Sales\Customers@duplicate')->name('customers.duplicate');
    Route::post('customers/import', 'Sales\Customers@import')->middleware('import')->name('customers.import');
    Route::get('customers/export', 'Sales\Customers@export')->name('customers.export');
    Route::resource('customers', 'Sales\Customers');
});

Route::group(['prefix' => 'purchases'], function () {
    Route::get('bills/{bill}/received', 'Purchases\Bills@markReceived')->name('bills.received');
    Route::get('bills/{bill}/cancelled', 'Purchases\Bills@markCancelled')->name('bills.cancelled');
    Route::get('bills/{bill}/restore', 'Purchases\Bills@restoreBill')->name('bills.restore');
    Route::get('bills/{bill}/print', 'Purchases\Bills@printBill')->name('bills.print');
    Route::get('bills/{bill}/pdf', 'Purchases\Bills@pdfBill')->name('bills.pdf');
    Route::get('bills/{bill}/duplicate', 'Purchases\Bills@duplicate')->name('bills.duplicate');
    Route::post('bills/import', 'Purchases\Bills@import')->middleware('import')->name('bills.import');
    Route::get('bills/export', 'Purchases\Bills@export')->name('bills.export');
    Route::resource('bills', 'Purchases\Bills', ['middleware' => ['date.format', 'money', 'dropzone']]);

    Route::get('recurring-bills/{recurring_bill}/duplicate', 'Purchases\RecurringBills@duplicate')->name('recurring-bills.duplicate');
    Route::get('recurring-bills/{recurring_bill}/end', 'Purchases\RecurringBills@end')->name('recurring-bills.end');
    Route::post('recurring-bills/import', 'Purchases\RecurringBills@import')->middleware('import')->name('recurring-bills.import');
    Route::get('recurring-bills/export', 'Purchases\RecurringBills@export')->name('recurring-bills.export');
    Route::resource('recurring-bills', 'Purchases\RecurringBills', ['middleware' => ['date.format', 'money', 'dropzone']]);

    Route::get('vendors/{vendor}/create-bill', 'Purchases\Vendors@createBill')->name('vendors.create-bill');
    Route::get('vendors/{vendor}/create-expense', 'Purchases\Vendors@createExpense')->name('vendors.create-expense');
    Route::get('vendors/{vendor}/enable', 'Purchases\Vendors@enable')->name('vendors.enable');
    Route::get('vendors/{vendor}/disable', 'Purchases\Vendors@disable')->name('vendors.disable');
    Route::get('vendors/{vendor}/duplicate', 'Purchases\Vendors@duplicate')->name('vendors.duplicate');
    Route::post('vendors/import', 'Purchases\Vendors@import')->middleware('import')->name('vendors.import');
    Route::get('vendors/export', 'Purchases\Vendors@export')->name('vendors.export');
    Route::resource('vendors', 'Purchases\Vendors', ['middleware' => ['dropzone']]);
});

Route::group(['prefix' => 'banking'], function () {
    Route::get('accounts/currency', 'Banking\Accounts@currency')->name('accounts.currency');
    Route::get('accounts/{account}/create-income', 'Banking\Accounts@createIncome')->name('accounts.create-income');
    Route::get('accounts/{account}/create-expense', 'Banking\Accounts@createExpense')->name('accounts.create-expense');
    Route::get('accounts/{account}/create-transfer', 'Banking\Accounts@createTransfer')->name('accounts.create-transfer');
    Route::get('accounts/{account}/see-performance', 'Banking\Accounts@seePerformance')->name('accounts.see-performance');
    Route::get('accounts/{account}/enable', 'Banking\Accounts@enable')->name('accounts.enable');
    Route::get('accounts/{account}/disable', 'Banking\Accounts@disable')->name('accounts.disable');
    Route::get('accounts/{account}/duplicate', 'Banking\Accounts@duplicate')->name('accounts.duplicate');
    Route::resource('accounts', 'Banking\Accounts', ['middleware' => ['date.format', 'money']]);

    Route::post('transactions/import', 'Banking\Transactions@import')->name('transactions.import');
    Route::get('transactions/export', 'Banking\Transactions@export')->name('transactions.export');

    Route::get('transactions/{transaction}/email', 'Banking\Transactions@emailTransaction')->name('transactions.email');
    Route::get('transactions/{transaction}/print', 'Banking\Transactions@printTransaction')->name('transactions.print');
    Route::get('transactions/{transaction}/pdf', 'Banking\Transactions@pdfTransaction')->name('transactions.pdf');
    Route::get('transactions/{transaction}/duplicate', 'Banking\Transactions@duplicate')->name('transactions.duplicate');
    Route::get('transactions/{transaction}/dial', 'Banking\Transactions@dial')->name('transactions.dial');
    Route::post('transactions/{transaction}/connect', 'Banking\Transactions@connect')->name('transactions.connect');
    Route::post('transactions/import', 'Banking\Transactions@import')->middleware('import')->name('transactions.import');
    Route::get('transactions/export', 'Banking\Transactions@export')->name('transactions.export');
    Route::resource('transactions', 'Banking\Transactions', ['middleware' => ['date.format', 'money', 'dropzone']]);

    Route::get('recurring-transactions/{recurring_transaction}/duplicate', 'Banking\RecurringTransactions@duplicate')->name('recurring-transactions.duplicate');
    Route::get('recurring-transactions/{recurring_transaction}/end', 'Banking\RecurringTransactions@end')->name('recurring-transactions.end');
    Route::post('recurring-transactions/import', 'Banking\RecurringTransactions@import')->middleware('import')->name('recurring-transactions.import');
    Route::get('recurring-transactions/export', 'Banking\RecurringTransactions@export')->name('recurring-transactions.export');
    Route::resource('recurring-transactions', 'Banking\RecurringTransactions', ['middleware' => ['date.format', 'money', 'dropzone']]);

    Route::get('transfers/{transfer}/print', 'Banking\Transfers@printTransfer')->name('transfers.print');
    Route::get('transfers/{transfer}/pdf', 'Banking\Transfers@pdfTransfer')->name('transfers.pdf');
    Route::get('transfers/{transfer}/duplicate', 'Banking\Transfers@duplicate')->name('transfers.duplicate');
    Route::post('transfers/import', 'Banking\Transfers@import')->middleware('import')->name('transfers.import');
    Route::get('transfers/export', 'Banking\Transfers@export')->name('transfers.export');
    Route::resource('transfers', 'Banking\Transfers', ['middleware' => ['date.format', 'money', 'dropzone']]);

    Route::post('reconciliations/calculate', 'Banking\Reconciliations@calculate')->middleware(['money']);
    Route::patch('reconciliations/calculate', 'Banking\Reconciliations@calculate')->middleware(['money']);
    Route::resource('reconciliations', 'Banking\Reconciliations', ['middleware' => ['date.format', 'money', 'dropzone']]);
});

Route::group(['prefix' => 'settings'], function () {
    Route::get('categories/{category}/enable', 'Settings\Categories@enable')->name('categories.enable');
    Route::get('categories/{category}/disable', 'Settings\Categories@disable')->name('categories.disable');
    Route::post('categories/import', 'Settings\Categories@import')->middleware('import')->name('categories.import');
    Route::get('categories/export', 'Settings\Categories@export')->name('categories.export');
    Route::resource('categories', 'Settings\Categories');

    Route::get('currencies/config', 'Settings\Currencies@config')->name('currencies.config');
    Route::get('currencies/{currency}/enable', 'Settings\Currencies@enable')->name('currencies.enable');
    Route::get('currencies/{currency}/disable', 'Settings\Currencies@disable')->name('currencies.disable');
    Route::resource('currencies', 'Settings\Currencies');

    Route::get('taxes/{tax}/enable', 'Settings\Taxes@enable')->name('taxes.enable');
    Route::get('taxes/{tax}/disable', 'Settings\Taxes@disable')->name('taxes.disable');
    Route::post('taxes/import', 'Settings\Taxes@import')->middleware('import')->name('taxes.import');
    Route::get('taxes/export', 'Settings\Taxes@export')->name('taxes.export');
    Route::resource('taxes', 'Settings\Taxes');

    Route::group(['as' => 'settings.'], function () {
        Route::get('company', 'Settings\Company@edit')->name('company.edit');
        Route::patch('company', 'Settings\Company@update')->middleware('dropzone')->name('company.update');
        Route::get('localisation', 'Settings\Localisation@edit')->name('localisation.edit');
        Route::patch('localisation', 'Settings\Localisation@update')->name('localisation.update');
        Route::get('invoice', 'Settings\Invoice@edit')->name('invoice.edit');
        Route::patch('invoice', 'Settings\Invoice@update')->name('invoice.update');
        Route::get('default', 'Settings\Defaults@edit')->name('default.edit');
        Route::patch('default', 'Settings\Defaults@update')->name('default.update');
        Route::get('email', 'Settings\Email@edit')->name('email.edit');
        Route::patch('email', 'Settings\Email@update')->name('email.update');
        Route::get('email-templates', 'Settings\EmailTemplates@edit')->name('email-templates.edit');
        Route::patch('email-templates', 'Settings\EmailTemplates@update')->name('email-templates.update');
        Route::get('email-templates/get', 'Settings\EmailTemplates@get')->name('email-templates.get');
        Route::get('schedule', 'Settings\Schedule@edit')->name('schedule.edit');
        Route::patch('schedule', 'Settings\Schedule@update')->name('schedule.update');
    });
});

Route::group(['as' => 'settings.'], function () {
    Route::get('{alias}/settings', 'Settings\Modules@edit')->name('module.edit');
    Route::patch('{alias}/settings', 'Settings\Modules@update')->middleware('dropzone')->name('module.update');
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
        Route::post('{type}/load-more', 'Modules\Tiles@loadMore')->name('load-more');
        Route::post('steps', 'Modules\Item@steps')->name('steps');
        Route::post('download', 'Modules\Item@download')->name('download');
        Route::post('unzip', 'Modules\Item@unzip')->name('unzip');
        Route::post('copy', 'Modules\Item@copy')->name('copy');
        Route::post('install', 'Modules\Item@install')->name('install');

        Route::post('{alias}/releases', 'Modules\Item@releases')->name('app.releases');
        Route::post('{alias}/reviews', 'Modules\Item@reviews')->name('app.reviews');
        Route::get('{alias}/uninstall', 'Modules\Item@uninstall')->name('app.uninstall');
        Route::get('{alias}/enable', 'Modules\Item@enable')->name('app.enable');
        Route::get('{alias}/disable', 'Modules\Item@disable')->name('app.disable');
        Route::get('{alias}', 'Modules\Item@show')->name('app.show');
    });
});

Route::group(['prefix' => 'install'], function () {
    Route::get('updates', 'Install\Updates@index')->name('updates.index');
    Route::get('updates/changelog', 'Install\Updates@changelog')->name('updates.changelog');
    Route::get('updates/check', 'Install\Updates@check')->name('updates.check');
    Route::get('updates/run/{alias}/{version}', 'Install\Updates@run')->name('updates.run');
    Route::post('updates/steps', 'Install\Updates@steps')->name('updates.steps');
    Route::post('updates/download', 'Install\Updates@download')->middleware('api.key')->name('updates.download');
    Route::post('updates/unzip', 'Install\Updates@unzip')->middleware('api.key')->name('updates.unzip');
    Route::post('updates/copy-files', 'Install\Updates@copyFiles')->middleware('api.key')->name('updates.copy');
    Route::post('updates/migrate', 'Install\Updates@migrate')->name('updates.migrate');
    Route::post('updates/finish', 'Install\Updates@finish')->name('updates.finish');
    Route::post('updates/redirect', 'Install\Updates@redirect')->name('updates.redirect');
});

Route::group(['as' => 'modals.', 'prefix' => 'modals'], function () {
    Route::resource('accounts', 'Modals\Accounts');
    Route::resource('categories', 'Modals\Categories');
    Route::resource('currencies', 'Modals\Currencies');
    Route::resource('customers', 'Modals\Customers');
    Route::resource('companies', 'Modals\Companies');
    Route::resource('vendors', 'Modals\Vendors');
    Route::resource('items', 'Modals\Items');
    Route::patch('invoice-templates', 'Modals\InvoiceTemplates@update')->name('invoice-templates.update');
    Route::patch('transfer-templates', 'Modals\TransferTemplates@update')->name('transfer-templates.update');
    Route::get('documents/item-columns/edit', 'Modals\DocumentItemColumns@edit')->name('documents.item-columns.edit');
    Route::patch('documents/item-columns', 'Modals\DocumentItemColumns@update')->name('documents.item-columns.update');
    Route::resource('documents/{document}/transactions', 'Modals\DocumentTransactions', [
        'names' => 'documents.document.transactions',
        'middleware' => ['date.format', 'money', 'dropzone']
    ]);

    Route::get('invoices/{invoice}/emails/create', 'Modals\InvoiceEmails@create')->name('invoices.emails.create');
    Route::post('invoices/{invoice}/emails', 'Modals\InvoiceEmails@store')->name('invoices.emails.store');
    Route::get('invoices/{invoice}/share/create', 'Modals\InvoiceShare@create')->name('invoices.share.create');

    Route::get('transactions/{transaction}/emails/create', 'Modals\TransactionEmails@create')->name('transactions.emails.create');
    Route::post('transactions/{transaction}/emails', 'Modals\TransactionEmails@store')->name('transactions.emails.store');
    Route::get('transactions/{transaction}/share/create', 'Modals\TransactionShare@create')->name('transactions.share.create');

    Route::resource('taxes', 'Modals\Taxes');
});

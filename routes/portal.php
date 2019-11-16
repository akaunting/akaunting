<?php

Route::group(['as' => 'portal.'], function () {
    Route::get('invoices/{invoice}/print', 'Portal\Invoices@printInvoice')->name('invoices.print');
    Route::get('invoices/{invoice}/pdf', 'Portal\Invoices@pdfInvoice')->name('invoices.pdf');
    Route::post('invoices/{invoice}/payment', 'Portal\Invoices@payment')->name('invoices.payment');
    Route::post('invoices/{invoice}/confirm', 'Portal\Invoices@confirm')->name('invoices.confirm');
    Route::resource('invoices', 'Portal\Invoices');

    Route::resource('payments', 'Portal\Payments');

    Route::resource('transactions', 'Portal\Transactions');

    Route::get('profile/read-invoices', 'Portal\Profile@readOverdueInvoices')->name('invoices.read');
    Route::resource('profile', 'Portal\Profile');

    Route::get('logout', 'Auth\Login@destroy')->name('logout');
});

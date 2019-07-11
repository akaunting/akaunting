<?php

Route::group(['middleware' => 'language'], function () {
    Route::get('invoices/{invoice}', 'Customers\Invoices@link')->name('signed.invoices');
    Route::get('invoices/{invoice}/print', 'Customers\Invoices@printInvoice')->name('signed.invoices.print');
    Route::get('invoices/{invoice}/pdf', 'Customers\Invoices@pdfInvoice')->name('signed.invoices.pdf');
    Route::post('invoices/{invoice}/payment', 'Customers\Invoices@payment')->name('signed.invoices.payment');
    Route::post('invoices/{invoice}/confirm', 'Customers\Invoices@confirm')->name('signed.invoices.confirm');
});

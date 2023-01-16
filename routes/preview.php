<?php

use Illuminate\Support\Facades\Route;

/**
 * 'preview' middleware and prefix applied to all routes
 *
 * @see \App\Providers\Route::mapPreviewRoutes
 * @see \modules\OfflinePayments\Routes\preview.php for module example
 */

Route::group(['as' => 'preview.'], function () {
    Route::get('invoices/{invoice}', 'Portal\Invoices@preview')->name('invoices.show');
    Route::get('invoices/{invoice}/print', 'Portal\Invoices@printInvoice')->name('invoices.print');
    Route::get('invoices/{invoice}/pdf', 'Portal\Invoices@pdfInvoice')->name('invoices.pdf');
    Route::post('invoices/{invoice}/payment', 'Portal\Invoices@payment')->name('invoices.payment');
    Route::post('invoices/{invoice}/confirm', 'Portal\Invoices@confirm')->name('invoices.confirm');
    Route::get('invoices/{invoice}/finish', 'Portal\Invoices@finish')->name('invoices.finish');

    Route::get('payments/{payment}', 'Portal\Payments@preview')->name('payments.show');
    Route::get('payments/{payment}/print', 'Portal\Payments@printPayment')->name('payments.print');
    Route::get('payments/{payment}/pdf', 'Portal\Payments@pdfPayment')->name('payments.pdf');
});

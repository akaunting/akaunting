<?php

use Illuminate\Support\Facades\Route;

/**
 * 'signed' middleware and prefix applied to all routes
 *
 * @see \App\Providers\Route::mapSignedRoutes
 * @see \modules\OfflinePayments\Routes\signed.php for module example
 */

Route::get('invoices/{invoice}', 'Portal\Invoices@signed')->name('signed.invoices.show');
Route::get('invoices/{invoice}/print', 'Portal\Invoices@printInvoice')->name('signed.invoices.print');
Route::get('invoices/{invoice}/pdf', 'Portal\Invoices@pdfInvoice')->name('signed.invoices.pdf');
Route::post('invoices/{invoice}/payment', 'Portal\Invoices@payment')->name('signed.invoices.payment');
Route::post('invoices/{invoice}/confirm', 'Portal\Invoices@confirm')->name('signed.invoices.confirm');

Route::get('payments/{payment}', 'Portal\Payments@signed')->name('signed.payments.show');
Route::get('payments/{payment}/print', 'Portal\Payments@printPayment')->name('signed.payments.print');
Route::get('payments/{payment}/pdf', 'Portal\Payments@pdfPayment')->name('signed.payments.pdf');

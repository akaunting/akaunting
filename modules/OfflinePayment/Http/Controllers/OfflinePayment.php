<?php

namespace Modules\OfflinePayment\Http\Controllers;

use App\Events\InvoicePaid;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\Customer\InvoicePayment as PaymentRequest;
use App\Http\Requests\Customer\InvoiceConfirm as ConfirmRequest;

use App\Models\Income\Invoice;

class OfflinePayment extends Controller
{
    /**
     * Show the form for editing the specified resource.
     * @param Invoice
     * @param PaymentRequest
     * @return Response
     */
    public function show(Invoice $invoice, PaymentRequest $request)
    {
        $gateway = [];

        $payment_methods = json_decode(setting('offlinepayment.methods'), true);

        foreach ($payment_methods as $payment_method) {
            if ($payment_method['code'] == $request['payment_method']) {
                $gateway = $payment_method;

                break;
            }
        }

        $html = view('offlinepayment::show', compact('gateway', 'invoice'))->render();

        return response()->json([
            'code' => $gateway['code'],
            'name' => $gateway['name'],
            'description' => $gateway['description'],
            'redirect' => false,
            'html' => $html,
        ]);
    }

    public function confirm(Invoice $invoice, Request $request)
    {
        $message = trans('messages.success.added', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        $request_invoice_paid = [
            'amount' => $invoice->amount,
            'currency_code' => $invoice->currency_code,
            'currency_rate' => $invoice->currency_rate,
            'payment_method' => $request['payment_method'],
        ];

        event(new InvoicePaid($invoice, $request_invoice_paid));

        return response()->json([
            'error' => false,
            'success' => true,
        ]);
    }
}

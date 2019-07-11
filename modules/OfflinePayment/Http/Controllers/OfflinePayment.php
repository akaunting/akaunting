<?php

namespace Modules\OfflinePayment\Http\Controllers;

use App\Events\InvoicePaid;

use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\Customer\InvoicePayment as PaymentRequest;
use App\Http\Requests\Customer\InvoiceConfirm as ConfirmRequest;

use App\Models\Income\Invoice;
use SignedUrl;

class OfflinePayment extends Controller
{

    /**
     * Show the form for editing the specified resource.
     * @param Invoice
     * @param PaymentRequest
     * @return JSON
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

    /**
     * Show the form for editing the specified resource.
     * @param $invoice
     * @param $request
     * @return JSON
     */
    public function link(Invoice $invoice, PaymentRequest $request)
    {
        $gateway = [];

        $payment_methods = json_decode(setting('offlinepayment.methods'), true);

        foreach ($payment_methods as $payment_method) {
            if ($payment_method['code'] == $request['payment_method']) {
                $gateway = $payment_method;

                break;
            }
        }

        $confirm_action = SignedUrl::sign(url('signed/invoices/' . $invoice->id . '/offlinepayment/confirm'), 1);

        $html = view('offlinepayment::link', compact('gateway', 'invoice', 'confirm_action'))->render();

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

        $event_response = event(new InvoicePaid($invoice, [
            'amount' => $invoice->amount,
            'payment_method' => $request['payment_method'],
        ]));

        return response()->json([
            'error' => false,
            'success' => true,
        ]);
    }
}

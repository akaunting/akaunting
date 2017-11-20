<?php

namespace Modules\OfflinePayment\Http\Controllers;

use App\Events\InvoicePaid;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

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
}

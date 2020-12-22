<?php

namespace Modules\OfflinePayments\Http\Controllers;

use App\Abstracts\Http\PaymentController;
use \App\Events\Sale\PaymentReceived;
use App\Http\Requests\Portal\InvoicePayment as PaymentRequest;
use App\Models\Sale\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class Payment extends PaymentController
{
    public $alias = 'offline-payments';

    public $type = 'redirect';

    public function show(Invoice $invoice, PaymentRequest $request)
    {
        $setting = [];

        $payment_methods = json_decode(setting('offline-payments.methods'), true);

        foreach ($payment_methods as $payment_method) {
            if ($payment_method['code'] == $request['payment_method']) {
                $setting = $payment_method;

                break;
            }
        }

        $html = view('offline-payments::show', compact('setting', 'invoice'))->render();

        return response()->json([
            'code' => $setting['code'],
            'name' => $setting['name'],
            'description' => $setting['description'],
            'redirect' => false,
            'html' => $html,
        ]);
    }

    public function signed(Invoice $invoice, PaymentRequest $request)
    {
        $setting = [];

        $payment_methods = json_decode(setting('offline-payments.methods'), true);

        foreach ($payment_methods as $payment_method) {
            if ($payment_method['code'] == $request['payment_method']) {
                $setting = $payment_method;

                break;
            }
        }

        $confirm_url = URL::signedRoute('signed.invoices.offline-payments.confirm', [$invoice->id, 'company_id' => session('company_id')]);

        $html = view('offline-payments::signed', compact('setting', 'invoice', 'confirm_url'))->render();

        return response()->json([
            'code' => $setting['code'],
            'name' => $setting['name'],
            'description' => $setting['description'],
            'redirect' => false,
            'html' => $html,
        ]);
    }

    public function confirm(Invoice $invoice, Request $request)
    {
        try {
            event(new PaymentReceived($invoice, $request));

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            $response = [
                'success' => true,
                'error' => false,
                'message' => $message,
                'data' => false,
            ];
        } catch(\Exception $e) {
            $message = $e->getMessage();

            $response = [
                'success' => false,
                'error' => true,
                'message' => $message,
                'data' => false,
            ];
        }

        return response()->json($response);
    }
}

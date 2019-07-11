<?php

namespace App\Listeners\Incomes\Invoice;

use App\Events\InvoicePaid;
use App\Http\Requests\Income\InvoicePayment as PaymentRequest;
use App\Jobs\Income\CreateInvoicePayment;
use App\Notifications\Customer\Invoice as Notification;
use App\Traits\DateTime;
use Date;

class Paid
{
    use DateTime;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return array
     */
    public function handle(InvoicePaid $event)
    {
        $invoice = $event->invoice;
        $request = $event->request;

        $invoice_payment = $this->createPayment($invoice, $request);

        if ($request['amount'] > $invoice->amount) {
            $message = trans('messages.error.added', ['type' => trans_choice('general.payment', 1)]);

            return [
                'success' => false,
                'error' => $message,
            ];
        } elseif ($request['amount'] == $invoice->amount) {
            $invoice->invoice_status_code = 'paid';
        } else {
            $invoice->invoice_status_code = 'partial';
        }

        $invoice->save();

        // Customer add payment on invoice send user notification
        foreach ($invoice->company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new Notification($invoice, $invoice_payment));
        }

        return [
            'success' => true,
            'error' => false,
        ];
    }

    protected function createPayment($invoice, $request)
    {
        if (!is_array($request)) {
            $request = $request->input();
        }

        $request['invoice_id'] = $invoice->id;
        $request['paid_at'] = Date::parse('now')->format('Y-m-d');
        $request['company_id'] = isset($request['company_id']) ? $request['company_id'] : session('company_id');
        $request['account_id'] = isset($request['account_id']) ? $request['account_id'] : setting('general.default_account');
        $request['payment_method'] = isset($request['payment_method']) ? $request['payment_method'] : setting('general.default_payment_method');
        $request['currency_code'] = isset($request['currency_code']) ? $request['currency_code'] : $invoice->currency_code;
        $request['currency_rate'] = isset($request['currency_rate']) ? $request['currency_rate'] : $invoice->currency_rate;
        $request['notify'] = isset($request['notify']) ? $request['notify'] : 0;

        $payment_request = new PaymentRequest();
        $payment_request->merge($request);

        $invoice_payment = dispatch(new CreateInvoicePayment($payment_request, $invoice));

        return $invoice_payment;
    }
}

<?php

namespace App\Listeners\Incomes\Invoice;

use App\Events\InvoicePaid;

use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceHistory;
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
     * @return void
     */
    public function handle(InvoicePaid $event)
    {
        $invoice = $event->invoice;
        $request = $event->request;

        $request['invoice_id'] = $invoice->id;

        if (!isset($request['company_id'])) {
            $request['company_id'] = session('company_id');
        }

        if (!isset($request['account_id'])) {
            $request['account_id'] = setting('general.default_account');
        }

        if (!isset($request['amount'])) {
            $request['amount'] = $invoice->amount;
        }

        if (!isset($request['currency_code'])) {
            $request['currency_code'] = $invoice->currency_code;
        }

        if (!isset($request['currency_rate'])) {
            $request['currency_rate'] = $invoice->currency_rate;
        }

        if (!isset($request['notify'])) {
            $request['notify'] = 0;
        }

        $request['paid_at'] = Date::parse('now')->format('Y-m-d');

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

        if (!is_array($request)) {
            $invoice_payment = InvoicePayment::create($request->input());
        } else {
            $invoice_payment = InvoicePayment::create($request);
        }

        $request['status_code'] = $invoice->invoice_status_code;

        $desc_date = Date::parse($request['paid_at'])->format($this->getCompanyDateFormat());

        $desc_amount = money((float) $request['amount'], $request['currency_code'], true)->format();

        $request['description'] = $desc_date . ' ' . $desc_amount;

        if (!is_array($request)) {
            $invoice_history = InvoiceHistory::create($request->input());
        } else {
            $invoice_history = InvoiceHistory::create($request);
        }

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
}

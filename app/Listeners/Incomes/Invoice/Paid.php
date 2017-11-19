<?php

namespace App\Listeners\Incomes\Invoice;

use App\Events\InvoicePaid;

use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceHistory;

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
        $request['account_id'] = setting('general.default_account');

        if (!isset($request['amount'])) {
            $request['amount'] = $invoice->amount;
        }

        $request['currency_code'] = $invoice->currency_code;
        $request['currency_rate'] = $invoice->currency_rate;

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

        InvoicePayment::create($request->input());

        $request['status_code'] = $invoice->invoice_status_code;

        $request['notify'] = 0;

        $desc_date = Date::parse($request['paid_at'])->format($this->getCompanyDateFormat());

        $desc_amount = money((float) $request['amount'], $request['currency_code'], true)->format();

        $request['description'] = $desc_date . ' ' . $desc_amount;

        InvoiceHistory::create($request->input());

        return [
            'success' => true,
            'error' => false,
        ];
    }
}

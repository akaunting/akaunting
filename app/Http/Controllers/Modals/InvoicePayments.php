<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Income\InvoicePayment as Request;
use App\Models\Income\Invoice;
use App\Models\Banking\Account;
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceHistory;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use App\Traits\Uploads;
use App\Jobs\Income\CreateInvoicePayment;

class InvoicePayments extends Controller
{
    use Uploads;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-incomes-invoices')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-incomes-invoices')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-incomes-invoices')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-incomes-invoices')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Invoice $invoice)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $invoice->currency_code)->first();

        $payment_methods = Modules::getPaymentMethods();

        $paid = $this->getPaid($invoice);

        // Get Invoice Totals
        foreach ($invoice->totals as $invoice_total) {
            $invoice->{$invoice_total->code} = $invoice_total->amount;
        }

        $total = money($invoice->total, $currency->code, true)->format();

        $invoice->grand_total = money($total, $currency->code)->getAmount();

        if (!empty($paid)) {
            $invoice->grand_total = round($invoice->total - $paid, $currency->precision) ;
        }

        $rand = rand();

        $html = view('modals.invoices.payment', compact('invoice', 'accounts', 'currencies', 'currency', 'payment_methods', 'rand'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Invoice $invoice, Request $request)
    {
        // Get currency object
        $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        $total_amount = $invoice->amount;

        $default_amount = (double) $request['amount'];

        if ($invoice->currency_code == $request['currency_code']) {
            $amount = $default_amount;
        } else {
            $default_amount_model = new InvoicePayment();

            $default_amount_model->default_currency_code = $invoice->currency_code;
            $default_amount_model->amount                = $default_amount;
            $default_amount_model->currency_code         = $request['currency_code'];
            $default_amount_model->currency_rate         = $currencies[$request['currency_code']];

            $default_amount = (double) $default_amount_model->getDivideConvertedAmount();

            $convert_amount = new InvoicePayment();

            $convert_amount->default_currency_code = $request['currency_code'];
            $convert_amount->amount = $default_amount;
            $convert_amount->currency_code = $invoice->currency_code;
            $convert_amount->currency_rate = $currencies[$invoice->currency_code];

            $amount = (double) $convert_amount->getDynamicConvertedAmount();
        }

        if ($invoice->payments()->count()) {
            $total_amount -= $this->getPaid($invoice);
        }

        // For amount cover integer
        $multiplier = 1;

        for ($i = 0; $i < $currency->precision; $i++) {
            $multiplier *= 10;
        }

        $amount_check = (int) ($amount * $multiplier);
        $total_amount_check = (int) (round($total_amount, $currency->precision) * $multiplier);

        if ($amount_check > $total_amount_check) {
            $error_amount = $total_amount;

            if ($invoice->currency_code != $request['currency_code']) {
                $error_amount_model = new InvoicePayment();

                $error_amount_model->default_currency_code = $request['currency_code'];
                $error_amount_model->amount                = $error_amount;
                $error_amount_model->currency_code         = $invoice->currency_code;
                $error_amount_model->currency_rate         = $currencies[$invoice->currency_code];

                $error_amount = (double) $error_amount_model->getDivideConvertedAmount();

                $convert_amount = new InvoicePayment();

                $convert_amount->default_currency_code = $invoice->currency_code;
                $convert_amount->amount = $error_amount;
                $convert_amount->currency_code = $request['currency_code'];
                $convert_amount->currency_rate = $currencies[$request['currency_code']];

                $error_amount = (double) $convert_amount->getDynamicConvertedAmount();
            }

            $message = trans('messages.error.over_payment', ['amount' => money($error_amount, $request['currency_code'], true)]);

            return response()->json([
                'success' => false,
                'error' => true,
                'data' => [
                    'amount' => $error_amount
                ],
                'message' => $message,
                'html' => 'null',
            ]);
        } elseif ($amount_check == $total_amount_check) {
            $invoice->invoice_status_code = 'paid';
        } else {
            $invoice->invoice_status_code = 'partial';
        }

        $invoice->save();

        $invoice_payment = dispatch(new CreateInvoicePayment($request, $invoice));

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'invoices');

            $invoice_payment->attachMedia($media, 'attachment');
        }

        $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $invoice_payment,
            'message' => $message,
            'html' => 'null',
        ]);
    }

    protected function getPaid($invoice)
    {
        $paid = 0;

        // Get Invoice Payments
        if ($invoice->payments->count()) {
            $_currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

            foreach ($invoice->payments as $item) {
                $default_amount = $item->amount;

                if ($invoice->currency_code == $item->currency_code) {
                    $amount = (double) $default_amount;
                } else {
                    $default_amount_model = new InvoicePayment();

                    $default_amount_model->default_currency_code = $invoice->currency_code;
                    $default_amount_model->amount = $default_amount;
                    $default_amount_model->currency_code = $item->currency_code;
                    $default_amount_model->currency_rate = $_currencies[$item->currency_code];

                    $default_amount = (double) $default_amount_model->getDivideConvertedAmount();

                    $convert_amount = new InvoicePayment();

                    $convert_amount->default_currency_code = $item->currency_code;
                    $convert_amount->amount = $default_amount;
                    $convert_amount->currency_code = $invoice->currency_code;
                    $convert_amount->currency_rate = $_currencies[$invoice->currency_code];

                    $amount = (double) $convert_amount->getDynamicConvertedAmount();
                }

                $paid += $amount;
            }
        }

        return $paid;
    }
}

<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Events\Income\PaymentReceived;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Income\Invoice;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use App\Traits\Uploads;

class InvoiceTransactions extends Controller
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
     * @param  Invoice  $invoice
     *
     * @return Response
     */
    public function create(Invoice $invoice)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $invoice->currency_code)->first();

        $payment_methods = Modules::getPaymentMethods();

        $paid = $this->getPaidAmount($invoice);

        // Get Invoice Totals
        foreach ($invoice->totals as $invoice_total) {
            $invoice->{$invoice_total->code} = $invoice_total->amount;
        }

        $total = money($invoice->total, $currency->code, true)->format();
        //$total = money(0, $currency->code, true)->format();

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
     * @param Invoice $invoice
     * @param Request $request
     *
     * @return Response
     */
    public function store(Invoice $invoice, Request $request)
    {
        try {
            event(new PaymentReceived($invoice, $request));

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();

            $response = [
                'success' => true,
                'error' => false,
                'message' => $message,
                'redirect' => route('invoices.show', $invoice->id),
            ];
        } catch(\Exception $e) {
            $message = $e->getMessage();

            //flash($message)->error();

            $response = [
                'success' => false,
                'error' => true,
                'message' => $message,
                'redirect' => null,
            ];
        }

        return response()->json($response);
    }

    protected function getPaidAmount($invoice)
    {
        $paid = 0;

        // Get Invoice Payments
        if (!$invoice->payments->count()) {
            return $paid;
        }

        $_currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

        foreach ($invoice->payments as $item) {
            $default_amount = $item->amount;

            if ($invoice->currency_code == $item->currency_code) {
                $amount = (double) $default_amount;
            } else {
                $default_amount_model = new Transaction();
                $default_amount_model->default_currency_code = $invoice->currency_code;
                $default_amount_model->amount = $default_amount;
                $default_amount_model->currency_code = $item->currency_code;
                $default_amount_model->currency_rate = $_currencies[$item->currency_code];

                $default_amount = (double) $default_amount_model->getDivideConvertedAmount();

                $convert_amount_model = new Transaction();
                $convert_amount_model->default_currency_code = $item->currency_code;
                $convert_amount_model->amount = $default_amount;
                $convert_amount_model->currency_code = $invoice->currency_code;
                $convert_amount_model->currency_rate = $_currencies[$invoice->currency_code];

                $amount = (double) $convert_amount_model->getAmountConvertedFromCustomDefault();
            }

            $paid += $amount;
        }

        return $paid;
    }
}

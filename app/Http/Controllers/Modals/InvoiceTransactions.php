<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateDocumentTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Sale\Invoice;
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
        $this->middleware('permission:create-sales-invoices')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-sales-invoices')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-sales-invoices')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-sales-invoices')->only('destroy');
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

        $paid = $invoice->paid;

        // Get Invoice Totals
        foreach ($invoice->totals as $invoice_total) {
            $invoice->{$invoice_total->code} = $invoice_total->amount;
        }

        $total = money($invoice->total, $currency->code, true)->format();

        $invoice->grand_total = money($total, $currency->code)->getAmount();

        if (!empty($paid)) {
            $invoice->grand_total = round($invoice->total - $paid, $currency->precision);
        }

        $html = view('modals.invoices.payment', compact('invoice', 'accounts', 'currencies', 'currency', 'payment_methods'))->render();

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
        $response = $this->ajaxDispatch(new CreateDocumentTransaction($invoice, $request));

        if ($response['success']) {
            $response['redirect'] = route('invoices.show', $invoice->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}

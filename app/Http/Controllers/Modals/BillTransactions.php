<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateDocumentTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use App\Traits\Uploads;

class BillTransactions extends Controller
{
    use Uploads;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-purchases-bills')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-purchases-bills')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-purchases-bills')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-purchases-bills')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Bill  $bill
     *
     * @return Response
     */
    public function create(Bill $bill)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $bill->currency_code)->first();

        $payment_methods = Modules::getPaymentMethods();

        $paid = $bill->paid;

        // Get Bill Totals
        foreach ($bill->totals as $bill_total) {
            $bill->{$bill_total->code} = $bill_total->amount;
        }

        $total = money($bill->total, $currency->code, true)->format();

        $bill->grand_total = money($total, $currency->code)->getAmount();

        if (!empty($paid)) {
            $bill->grand_total = round($bill->total - $paid, $currency->precision);
        }

        $html = view('modals.bills.payment', compact('bill', 'accounts', 'currencies', 'currency', 'payment_methods'))->render();

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
     * @param  Bill  $bill
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Bill $bill, Request $request)
    {
        $response = $this->ajaxDispatch(new CreateDocumentTransaction($bill, $request));

        if ($response['success']) {
            $response['redirect'] = route('bills.show', $bill->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}

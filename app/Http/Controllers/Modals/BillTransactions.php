<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateDocumentTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Expense\Bill;
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
        $this->middleware('permission:create-expenses-bills')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-expenses-bills')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-expenses-bills')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-expenses-bills')->only('destroy');
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

        $paid = $this->getPaidAmount($bill);

        // Get Bill Totals
        foreach ($bill->totals as $bill_total) {
            $bill->{$bill_total->code} = $bill_total->amount;
        }

        $total = money($bill->total, $currency->code, true)->format();

        $bill->grand_total = money($total, $currency->code)->getAmount();

        if (!empty($paid)) {
            $bill->grand_total = round($bill->total - $paid, $currency->precision) ;
        }

        $rand = rand();

        $html = view('modals.bills.payment', compact('bill', 'accounts', 'currencies', 'currency', 'payment_methods', 'rand'))->render();

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
        try {
            $transaction = $this->dispatch(new CreateDocumentTransaction($bill, $request));

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            $response = [
                'success' => true,
                'error' => false,
                'message' => $message,
                'data' => $transaction,
            ];
        } catch(\Exception $e) {
            $response = [
                'success' => false,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => 'null',
            ];
        }

        return response()->json($response);
    }

    protected function getPaidAmount($bill)
    {
        $paid = 0;

        // Get Bill Payments
        if (!$bill->payments->count()) {
            return $paid;
        }

        $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

        foreach ($bill->payments as $item) {
            $default_amount = (double) $item->amount;

            if ($bill->currency_code == $item->currency_code) {
                $amount = $default_amount;
            } else {
                $default_amount_model = new Transaction();
                $default_amount_model->default_currency_code = $bill->currency_code;
                $default_amount_model->amount = $default_amount;
                $default_amount_model->currency_code = $item->currency_code;
                $default_amount_model->currency_rate = $currencies[$item->currency_code];

                $default_amount = (double) $default_amount_model->getDivideConvertedAmount();

                $convert_amount_model = new Transaction();
                $convert_amount_model->default_currency_code = $item->currency_code;
                $convert_amount_model->amount = $default_amount;
                $convert_amount_model->currency_code = $bill->currency_code;
                $convert_amount_model->currency_rate = $currencies[$bill->currency_code];

                $amount = (double) $convert_amount_model->getAmountConvertedFromCustomDefault();
            }

            $paid += $amount;
        }

        return $paid;
    }
}

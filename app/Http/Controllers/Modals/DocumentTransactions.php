<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Jobs\Banking\UpdateBankingDocumentTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use App\Traits\Currencies;
use App\Traits\Uploads;
use App\Traits\Transactions;
use Date;
use Illuminate\Support\Str;

class DocumentTransactions extends Controller
{
    use Currencies, Uploads, Transactions;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        //$this->middleware('permission:create-sales-invoices')->only('create', 'store', 'duplicate', 'import');
        //$this->middleware('permission:read-sales-invoices')->only('index', 'show', 'edit', 'export');
        //$this->middleware('permission:update-sales-invoices')->only('update', 'enable', 'disable');
        //$this->middleware('permission:delete-sales-invoices')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Document  $document
     *
     * @return Response
     */
    public function create(Document $document)
    {
        $currency = Currency::where('code', $document->currency_code)->first();

        $paid = $document->paid;

        $document->paid_amount = $paid;

        $number = $this->getNextTransactionNumber();

        // Get document Totals
        foreach ($document->totals as $document_total) {
            $document->{$document_total->code} = $document_total->amount;
        }

        $d_total = $document?->total ?? 0;

        $total = money($d_total, $currency->code)->format();

        $document->grand_total = money($total, $currency->code, false)->getAmount();

        if (! empty($paid)) {
            $document->grand_total = round($d_total - $paid, $currency->precision);
        }

        $amount =  $document->grand_total;

        $document->paid_at = Date::now()->toDateString();

        $buttons = [
            'cancel' => [
                'text' => trans('general.cancel'),
                'class' => 'btn-outline-secondary'
            ],
            'payment' => [
                'text' => trans('invoices.accept_payments'),
                'class' => 'long-texts',
                'before_text' => trans('general.get_paid_faster'),
                'class' => 'px-6 py-1.5 text-xs bg-gray-200 hover:bg-purple-200 font-medium rounded-lg leading-6 long-texts',
                'url' => route('apps.categories.show', [
                    'alias' => 'payment-method',
                    'utm_source' => $document->type . '_payment',
                    'utm_medium' => 'app',
                    'utm_campaign' => 'payment_method',
                ])
            ],
            'send' => [
                'text' => trans('general.save_and_send'),
                'class' => 'disabled:bg-green-100',
                'disabled' => empty($document->contact->has_email) ? true : false,
            ],
            'confirm' => [
                'text' => trans('general.save'),
                'class' => 'disabled:bg-green-100'
            ],
        ];

        $method = 'POST';

        $route = ['modals.documents.document.transactions.store', $document->id];

        $html = view('modals.documents.payment', compact('document', 'method', 'route', 'currency', 'number', 'amount'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('general.title.new', ['type' => trans_choice('general.payments', 1)]),
                'buttons' => $buttons,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Document $document
     * @param Request $request
     *
     * @return Response
     */
    public function store(Document $document, Request $request)
    {
        $response = $this->ajaxDispatch(new CreateBankingDocumentTransaction($document, $request));

        if ($response['success']) {
            $response['redirect'] = $this->getRedirectUrl($document, $request);

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Document  $document
     *
     * @return Response
     */
    public function edit(Document $document, Transaction $transaction)
    {
        $currency = Currency::where('code', $document->currency_code)->first();

        // if you edit transaction before remove transaction amount
        $paid = $document->paid - $transaction->amount_for_document;

        $document->paid_amount = $paid;

        $number = $transaction->number;

        $amount = money($transaction->amount_for_document, $currency->code, false)->getAmount();

        // Get document Totals
        foreach ($document->totals as $document_total) {
            $document->{$document_total->code} = $document_total->amount;
        }

        $d_total = $document?->total ?? 0;

        $total = money($d_total, $currency->code)->format();

        $document->grand_total = money($total, $currency->code, false)->getAmount();

        if (! empty($paid)) {
            $document->grand_total = round($d_total - $paid, $currency->precision);
        }

        $document->paid_at = $transaction->paid_at;

        $buttons = [
            'cancel' => [
                'text' => trans('general.cancel'),
                'class' => 'btn-outline-secondary'
            ],
            'payment' => [
                'text' => trans('invoices.accept_payments'),
                'before_text' => trans('general.get_paid_faster'),
                'class' => 'px-6 py-1.5 text-xs bg-gray-200 hover:bg-purple-200 font-medium rounded-lg leading-6 long-texts',
                'url' => route('apps.categories.show', [
                    'alias' => 'payment-method',
                    'utm_source' => $document->type . '_payment',
                    'utm_medium' => 'app',
                    'utm_campaign' => 'payment_method',
                ])
            ],
            'confirm' => [
                'text' => trans('general.save'),
                'class' => 'disabled:bg-green-100'
            ],
        ];

        $method = 'PATCH';

        $route = ['modals.documents.document.transactions.update', $document->id, $transaction->id];

        $html = view('modals.documents.payment', compact('document', 'transaction', 'method', 'route', 'currency', 'number', 'amount'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('general.title.edit', ['type' => trans_choice('general.payments', 1)]),
                'buttons' => $buttons,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $item
     * @param  $request
     * @return Response
     */
    public function update(Document $document, Transaction $transaction, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateBankingDocumentTransaction($document, $transaction, $request));

        if ($response['success']) {
            $route = config('type.document.' . $document->type . '.route.prefix');

            if ($alias = config('type.document.' . $document->type . '.alias')) {
                $route = $alias . '.' . $route;
            }

            $redirect = route($route . '.show', $document->id);

            $response['redirect'] = $this->getRedirectUrl($document, $request, $redirect);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction $transaction
     *
     * @return Response
     */
    public function destroy(Document $document, Transaction $transaction)
    {
        $response = $this->ajaxDispatch(new DeleteTransaction($transaction));

        $route = config('type.document.' . $document->type . '.route.prefix');

        if ($alias = config('type.document.' . $document->type . '.alias')) {
            $route = $alias . '.' . $route;
        }

        $response['redirect'] = route($route . '.show', $document->id);

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    protected function getRedirectUrl($document, $request, $url = null)
    {
        $redirect = $url ?? url()->previous();

        if ($request->has('sendtransaction')) {
            $paramaters = [
                $document->type => $document->id,
                'sendtransaction' => true,
            ];

            $quin = '?';
    
            if (Str::contains($redirect, '?')) {
                $quin = '&';
            }

            $redirect .= $quin . http_build_query($paramaters);
        }

        return $redirect;
    }

    protected function getTransactionConvertAmount($document, $transaction)
    {
        if (empty($document->amount)) {
            return 0;
        }

        $paid = 0;

        $code = $document->currency_code;
        $rate = $document->currency_rate;
        $precision = currency($code)->getPrecision();

        $amount = $transaction->amount;

        if ($code != $transaction->currency_code) {
            $amount = $this->convertBetween($amount, $transaction->currency_code, $transaction->currency_rate, $code, $rate);
        }

        $paid += $amount;

        return round($paid, $precision);
    }
}

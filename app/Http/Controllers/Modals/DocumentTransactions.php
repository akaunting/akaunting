<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Jobs\Banking\UpdateBankingDocumentTransaction;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use App\Traits\Uploads;
use App\Traits\Transactions;
use Date;


class DocumentTransactions extends Controller
{
    use Uploads, Transactions;

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

        $number = $this->getNextTransactionNumber();

        // Get document Totals
        foreach ($document->totals as $document_total) {
            $document->{$document_total->code} = $document_total->amount;
        }

        $total = money($document->total, $currency->code)->format();

        $document->grand_total = money($total, $currency->code, false)->getAmount();

        if (! empty($paid)) {
            $document->grand_total = round($document->total - $paid, $currency->precision);
        }

        $document->paid_at = Date::now()->toDateString();

        $buttons = [
            'cancel' => [
                'text' => trans('general.cancel'),
                'class' => 'btn-outline-secondary'
            ],
            'payment' => [
                'text' => trans('invoices.accept_payments'),
                'class' => 'long-texts',
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

        $method = 'POST';

        $route = ['modals.documents.document.transactions.store', $document->id];

        $html = view('modals.documents.payment', compact('document', 'method', 'route', 'currency', 'number'))->render();

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
            $response['redirect'] = url()->previous();

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

        $paid = $document->paid;

        $number = $transaction->number;

        $document->grand_total = money($transaction->amount, $currency->code, false)->getAmount();

        $document->paid_at = $transaction->paid_at;

        $buttons = [
            'cancel' => [
                'text' => trans('general.cancel'),
                'class' => 'btn-outline-secondary'
            ],
            'payment' => [
                'text' => trans('invoices.accept_payments'),
                'class' => 'long-texts',
                'url' => route('apps.categories.show', 'payment-method')
            ],
            'confirm' => [
                'text' => trans('general.save'),
                'class' => 'disabled:bg-green-100'
            ],
        ];

        $method = 'PATCH';

        $route = ['modals.documents.document.transactions.update', $document->id, $transaction->id];

        $html = view('modals.documents.payment', compact('document', 'transaction', 'method', 'route', 'currency', 'number'))->render();

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

            $response['redirect'] = route($route . '.show', $document->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}

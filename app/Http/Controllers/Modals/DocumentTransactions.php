<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use App\Traits\Uploads;

class DocumentTransactions extends Controller
{
    use Uploads;

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
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $document->currency_code)->first();

        $payment_methods = Modules::getPaymentMethods();

        $paid = $document->paid;

        // Get document Totals
        foreach ($document->totals as $document_total) {
            $document->{$document_total->code} = $document_total->amount;
        }

        $total = money($document->total, $currency->code, true)->format();

        $document->grand_total = money($total, $currency->code)->getAmount();

        if (!empty($paid)) {
            $document->grand_total = round($document->total - $paid, $currency->precision);
        }

        $html = view('modals.documents.payment', compact('document', 'accounts', 'currencies', 'currency', 'payment_methods'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('general.title.new', ['type' => trans_choice('general.payments', 1)]),
                'buttons' => [
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
                        'class' => 'btn-success'
                    ]
                ]
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
            $route = config('type.' . $document->type . '.route.prefix');

            if ($alias = config('type.' . $document->type . '.alias')) {
                $route = $alias . '.' . $route;
            }

            $response['redirect'] = route($route . '.show', $document->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}

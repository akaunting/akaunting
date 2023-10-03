<?php

namespace App\Http\Controllers\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\RecurringInvoices\RecurringInvoices as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Document\Document as Request;
use App\Imports\Sales\RecurringInvoices\RecurringInvoices as Import;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Common\Recurring;
use App\Models\Document\Document;
use App\Traits\Documents;

class RecurringInvoices extends Controller
{
    use Documents;

    /**
     * @var string
     */
    public $type = Document::INVOICE_RECURRING_TYPE;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-sales-invoices')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-sales-invoices')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-sales-invoices')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-sales-invoices')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $invoices = Document::with('contact', 'transactions', 'recurring')->invoiceRecurring()->collect(['issued_at' => 'desc']);

        return $this->response('sales.recurring_invoices.index', compact('invoices'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Document $recurring_invoice
     *
     * @return Response
     */
    public function show(Document $recurring_invoice)
    {
        $recurring_invoice->load(['category', 'recurring', 'children']);

        return view('sales.recurring_invoices.show', compact('recurring_invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('sales.recurring_invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateDocument($request->merge(['issued_at' => $request->get('recurring_started_at')])));

        if ($response['success']) {
            $response['redirect'] = route('recurring-invoices.show', $response['data']->id);

            $message = trans('messages.success.created', ['type' => trans_choice('general.recurring_invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('recurring-invoices.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Document $recurring_invoice
     *
     * @return Response
     */
    public function duplicate(Document $recurring_invoice)
    {
        $clone = $this->dispatch(new DuplicateDocument($recurring_invoice));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.recurring_invoices', 1)]);

        flash($message)->success();

        return redirect()->route('recurring-invoices.edit', $clone->id);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest  $request
     *
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('general.recurring_invoices', 2));

        if ($response['success']) {
            $response['redirect'] = route('recurring-invoices.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['sales', 'recurring-invoices']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Document $recurring_invoice
     *
     * @return Response
     */
    public function edit(Document $recurring_invoice)
    {
        return view('sales.recurring_invoices.edit', compact('recurring_invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Document $recurring_invoice
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Document $recurring_invoice, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDocument($recurring_invoice, $request->merge(['issued_at' => $request->get('recurring_started_at')])));

        if ($response['success']) {
            $response['redirect'] = route('recurring-invoices.show', $response['data']->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.recurring_invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('recurring-invoices.edit', $recurring_invoice->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /** 
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('general.recurring_invoices', 2));
    }

    /**
     * End recurring template.
     *
     * @return Response
     */
    public function end(Document $recurring_invoice)
    {
        $response = $this->ajaxDispatch(new UpdateDocument($recurring_invoice, [
            'recurring_frequency' => $recurring_invoice->recurring->frequency,
            'recurring_interval' => $recurring_invoice->recurring->interval,
            'recurring_started_at' => $recurring_invoice->recurring->started_at,
            'recurring_limit' => $recurring_invoice->recurring->limit,
            'recurring_limit_count' => $recurring_invoice->recurring->limit_count,
            'recurring_limit_date' => $recurring_invoice->recurring->limit_date,
            'created_from' => $recurring_invoice->created_from,
            'created_by' => $recurring_invoice->created_by,
            'recurring_status' => Recurring::END_STATUS,
        ]));

        if ($response['success']) {
            $message = trans('messages.success.ended', ['type' => trans_choice('general.recurring_invoices', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return redirect()->route('recurring-invoices.index');
    }
}

<?php

namespace App\Http\Controllers\Purchases;

use App\Abstracts\Http\Controller;
use App\Exports\Purchases\RecurringBills\RecurringBills as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Document\Document as Request;
use App\Imports\Purchases\RecurringBills\RecurringBills as Import;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Common\Recurring;
use App\Models\Document\Document;
use App\Traits\Documents;

class RecurringBills extends Controller
{
    use Documents;

    /**
     * @var string
     */
    public $type = Document::BILL_RECURRING_TYPE;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-purchases-bills')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-purchases-bills')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-purchases-bills')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-purchases-bills')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $bills = Document::with('contact', 'transactions', 'recurring')->billRecurring()->collect(['issued_at' => 'desc']);

        return $this->response('purchases.recurring_bills.index', compact('bills'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Document $recurring_bill
     *
     * @return Response
     */
    public function show(Document $recurring_bill)
    {
        $recurring_bill->load(['category', 'recurring', 'children']);

        return view('purchases.recurring_bills.show', compact('recurring_bill'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('purchases.recurring_bills.create');
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
            $response['redirect'] = route('recurring-bills.show', $response['data']->id);

            $message = trans('messages.success.created', ['type' => trans_choice('general.recurring_bills', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('recurring-bills.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Document $recurring_bill
     *
     * @return Response
     */
    public function duplicate(Document $recurring_bill)
    {
        $clone = $this->dispatch(new DuplicateDocument($recurring_bill));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.recurring_bills', 1)]);

        flash($message)->success();

        return redirect()->route('recurring-bills.edit', $clone->id);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.recurring_bills', 2));

        if ($response['success']) {
            $response['redirect'] = route('recurring-bills.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['purchases', 'recurring-bills']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Document $recurring_bill
     *
     * @return Response
     */
    public function edit(Document $recurring_bill)
    {
        return view('purchases.recurring_bills.edit', compact('recurring_bill'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Document $recurring_bill
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Document $recurring_bill, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDocument($recurring_bill, $request->merge(['issued_at' => $request->get('recurring_started_at')])));

        if ($response['success']) {
            $response['redirect'] = route('recurring-bills.show', $response['data']->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.recurring_bills', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('recurring-bills.edit', $recurring_bill->id);

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
        return $this->exportExcel(new Export, trans_choice('general.recurring_bills', 2));
    }

    /**
     * End recurring template.
     *
     * @return Response
     */
    public function end(Document $recurring_bill)
    {
        $response = $this->ajaxDispatch(new UpdateDocument($recurring_bill, [
            'recurring_frequency' => $recurring_bill->recurring->frequency,
            'recurring_interval' => $recurring_bill->recurring->interval,
            'recurring_started_at' => $recurring_bill->recurring->started_at,
            'recurring_limit' => $recurring_bill->recurring->limit,
            'recurring_limit_count' => $recurring_bill->recurring->limit_count,
            'recurring_limit_date' => $recurring_bill->recurring->limit_date,
            'created_from' => $recurring_bill->created_from,
            'created_by' => $recurring_bill->created_by,
            'recurring_status' => Recurring::END_STATUS,
        ]));

        if ($response['success']) {
            $message = trans('messages.success.ended', ['type' => trans_choice('general.recurring_bills', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return redirect()->route('recurring-bills.index');
    }
}

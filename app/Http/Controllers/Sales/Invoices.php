<?php

namespace App\Http\Controllers\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\Invoices\Invoices as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Document\Document as Request;
use App\Imports\Sales\Invoices\Invoices as Import;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\DownloadDocument;
use App\Jobs\Document\SendDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Traits\Documents;

class Invoices extends Controller
{
    use Documents;

    public string $type = Document::INVOICE_TYPE;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->setActiveTabForDocuments();

        $invoices = Document::invoice()->with('contact', 'items', 'item_taxes', 'last_history', 'transactions', 'totals', 'histories', 'media')->collect(['document_number'=> 'desc']);

        $total_invoices = Document::invoice()->count();

        return $this->response('sales.invoices.index', compact('invoices', 'total_invoices'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function show(Document $invoice)
    {
        $invoice->load([
            'items.taxes.tax',
            'items.item',
            'totals',
            'contact',
            'currency',
            'category',
            'histories',
            'media',
            'transactions',
            'recurring',
            'children',
        ]);

        return view('sales.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('sales.invoices.create');
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
        $response = $this->ajaxDispatch(new CreateDocument($request));

        if ($response['success']) {
            $paramaters = ['invoice' => $response['data']->id];

            if ($request->has('senddocument')) {
                $paramaters['senddocument'] = true;
            }

            $response['redirect'] = route('invoices.show', $paramaters);

            $message = trans('messages.success.created', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('invoices.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function duplicate(Document $invoice)
    {
        $clone = $this->dispatch(new DuplicateDocument($invoice));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->route('invoices.edit', $clone->id);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.invoices', 2));

        if ($response['success']) {
            $response['redirect'] = route('invoices.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['sales', 'invoices']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function edit(Document $invoice)
    {
        return view('sales.invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Document $invoice
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Document $invoice, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateDocument($invoice, $request));

        if ($response['success']) {
            $paramaters = ['invoice' => $response['data']->id];

            if ($request->has('senddocument')) {
                $paramaters['senddocument'] = true;
            }

            $response['redirect'] = route('invoices.show', $paramaters);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('invoices.edit', $invoice->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function destroy(Document $invoice)
    {
        $response = $this->ajaxDispatch(new DeleteDocument($invoice));

        $response['redirect'] = route('invoices.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
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
        return $this->exportExcel(new Export, trans_choice('general.invoices', 2));
    }

    /**
     * Mark the invoice as sent.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function markSent(Document $invoice)
    {
        event(new \App\Events\Document\DocumentMarkedSent($invoice));

        $message = trans('documents.messages.marked_sent', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Mark the invoice as cancelled.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function markCancelled(Document $invoice)
    {
        event(new \App\Events\Document\DocumentCancelled($invoice));

        $message = trans('documents.messages.marked_cancelled', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Restore the invoice.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function restoreInvoice(Document $invoice)
    {
        event(new \App\Events\Document\DocumentRestored($invoice));

        $message = trans('documents.messages.restored', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Download the PDF file of invoice.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function emailInvoice(Document $invoice)
    {
        if (empty($invoice->contact_email)) {
            return redirect()->back();
        }

        $response = $this->ajaxDispatch(new SendDocument($invoice));

        if ($response['success']) {
            $message = trans('documents.messages.email_sent', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return redirect()->back();
    }

    /**
     * Print the invoice.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function printInvoice(Document $invoice)
    {
        event(new \App\Events\Document\DocumentPrinting($invoice));

        $view = view($invoice->template_path, compact('invoice'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Download the PDF file of invoice.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function pdfInvoice(Document $invoice)
    {
        event(new \App\Events\Document\DocumentPrinting($invoice));

        return $this->dispatch(new DownloadDocument($invoice, null, null, false, 'download'));
    }
}

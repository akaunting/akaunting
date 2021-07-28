<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transfer as Request;
use App\Exports\Banking\Transfers as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Banking\Transfers as Import;
use App\Jobs\Banking\CreateTransfer;
use App\Jobs\Banking\UpdateTransfer;
use App\Jobs\Banking\DeleteTransfer;
use App\Models\Banking\Account;
use App\Models\Banking\Transfer;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use Date;
use Illuminate\Support\Str;

class Transfers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $transfers = Transfer::with(
            'expense_transaction', 'expense_transaction.account', 'income_transaction', 'income_transaction.account'
        )->collect(['expense_transaction.paid_at' => 'desc']);

        return $this->response('banking.transfers.index', compact('transfers'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show(Transfer $transfer)
    {
        return view('banking.transfers.show', compact('transfer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $currency = Currency::where('code', setting('default.currency'))->first();

        $file_types = $this->prepeareFileTypes();

        return view('banking.transfers.create', compact('accounts', 'payment_methods', 'currency', 'file_types'));
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
        $response = $this->ajaxDispatch(new CreateTransfer($request));

        if ($response['success']) {
            $response['redirect'] = route('transfers.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.transfers', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('transfers.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Transfer $transfer
     *
     * @return Response
     */
    public function duplicate(Transfer $transfer)
    {
        $clone = $transfer->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.transfers', 1)]);

        flash($message)->success();

        return redirect()->route('transfers.show', $clone->id);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.transfers', 2));

        if ($response['success']) {
            $response['redirect'] = route('transfers.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['banking', 'transfers']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transfer  $transfer
     *
     * @return Response
     */
    public function edit(Transfer $transfer)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $account = $transfer->expense_transaction->account;

        $currency = Currency::where('code', $account->currency_code)->first();

        $file_types = $this->prepeareFileTypes();

        return view('banking.transfers.edit', compact('transfer', 'accounts', 'payment_methods', 'currency', 'file_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $id
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Transfer $transfer, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTransfer($transfer, $request));

        if ($response['success']) {
            $response['redirect'] = route('transfers.show', $transfer->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.transfers', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('transfers.edit', $transfer->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     *
     * @return Response
     */
    public function destroy(Transfer $transfer)
    {
        $response = $this->ajaxDispatch(new DeleteTransfer($transfer));

        $response['redirect'] = route('transfers.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.transfers', 1)]);

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
        return $this->exportExcel(new Export, trans_choice('general.transfers', 2));
    }

    /**
     * Print the transfer.
     *
     * @param  Transfer $transfer
     *
     * @return Response
     */
    public function printTransfer(Transfer $transfer)
    {
        event(new \App\Events\Banking\TransferPrinting($transfer));

        $view = view($transfer->template_path, compact('transfer'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Download the PDF file of transfer.
     *
     * @param  Transfer $transfer
     *
     * @return Response
     */
    public function pdfTransfer(Transfer $transfer)
    {
        event(new \App\Events\Banking\TransferPrinting($transfer));

        $currency_style = true;

        $view = view($transfer->template_path, compact('transfer', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = trans_choice('general.transfers', 1) . '-' . Str::slug($transfer->id, '-', language()->getShortCode()) . '-' . time() . '.pdf';

        return $pdf->download($file_name);
    }

    protected function prepeareFileTypes()
    {
        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $file_types = implode(',', $file_types);

        return $file_types;
    }
}

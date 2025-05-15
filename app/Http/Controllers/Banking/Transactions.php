<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Events\Banking\TransactionPrinting;
use App\Events\Banking\TransactionSent;
use App\Exports\Banking\Transactions as Export;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Requests\Banking\TransactionConnect;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Banking\Transactions as Import;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Banking\DuplicateTransaction;
use App\Jobs\Banking\SendTransaction;
use App\Jobs\Banking\MatchBankingDocumentTransaction;
use App\Jobs\Banking\SplitTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Setting\Currency;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Transactions as TransactionsTrait;
use App\Models\Setting\Tax;

class Transactions extends Controller
{
    use Currencies, DateTime, TransactionsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->setActiveTabForTransactions();

        $transactions = Transaction::with('account', 'category', 'contact', 'taxes')->collect(['paid_at'=> 'desc']);

        $total_transactions = Transaction::count();

        $totals = [
            'income' => 0,
            'expense' => 0,
            'profit' => 0,
        ];

        $transactions->each(function ($transaction) use (&$totals) {
            if (($transaction->isNotIncome() && $transaction->isNotExpense()) || $transaction->isTransferTransaction()) {
                return;
            }

            $type = $transaction->isIncome() ? 'income' : 'expense';

            $totals[$type] += $transaction->getAmountConvertedToDefault();
        });

        $totals['profit'] = $totals['income'] - $totals['expense'];

        $incoming_amount = money($totals['income']);
        $expense_amount = money($totals['expense']);
        $profit_amount = money($totals['profit']);

        $summary_amounts = [
            'incoming_exact'        => $incoming_amount->format(),
            'incoming_for_humans'   => $incoming_amount->formatForHumans(),
            'expense_exact'         => $expense_amount->format(),
            'expense_for_humans'    => $expense_amount->formatForHumans(),
            'profit_exact'          => $profit_amount->format(),
            'profit_for_humans'     => $profit_amount->formatForHumans(),
        ];

        $search_type = search_string_value('type');
        $type = empty($search_type) ? 'transactions' : (($search_type == 'income') ? 'income' : 'expense');

        return $this->response('banking.transactions.index', compact(
            'type',
            'transactions',
            'total_transactions',
            'summary_amounts'
        ));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('taxes');

        $title = $transaction->isIncome() ? trans_choice('general.receipts', 1) : trans('transactions.payment_made');
        $real_type = $this->getRealTypeTransaction($transaction->type);

        return view('banking.transactions.show', compact('transaction', 'title', 'real_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $type = $this->getTypeTransaction(request()->get('type', 'income'));
        $real_type = $this->getRealTypeTransaction($type);

        $number = $this->getNextTransactionNumber($type);

        $contact_type = config('type.transaction.' . $type . '.contact_type');

        $account_currency_code = Account::where('id', setting('default.account'))->pluck('currency_code')->first();

        $currency = Currency::where('code', $account_currency_code)->first();

        $taxes = Tax::enabled()->orderBy('name')->get();

        return view('banking.transactions.create', compact(
            'type',
            'real_type',
            'number',
            'contact_type',
            'account_currency_code',
            'currency',
            'taxes'
        ));
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
        $response = $this->ajaxDispatch(new CreateTransaction($request));

        if ($response['success']) {
            $response['redirect'] = route('transactions.show', $response['data']->id);

            $message = trans('messages.success.created', ['type' => trans_choice('general.transactions', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('transactions.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Transaction  $transaction
     *
     * @return Response
     */
    public function duplicate(Transaction $transaction)
    {
        $clone = $this->dispatch(new DuplicateTransaction($transaction));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.transactions', 1)]);

        flash($message)->success();

        return redirect()->route('transactions.edit', $clone->id);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.transactions', 2));

        if ($response['success']) {
            $response['redirect'] = route('transactions.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['banking', 'transactions']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transaction  $transaction
     *
     * @return Response
     */
    public function edit(Transaction $transaction)
    {
        $type = $transaction->type;
        $contact_type = config('type.transaction.' . $type . '.contact_type');

        $currency = Currency::where('code', $transaction->currency_code)->first();

        $taxes = Tax::enabled()->orderBy('name')->get();

        $date_format = $this->getCompanyDateFormat();

        return view('banking.transactions.edit', compact(
            'type',
            'contact_type',
            'transaction',
            'currency',
            'taxes',
            'date_format'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transaction $transaction
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Transaction $transaction, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTransaction($transaction, $request));

        if ($response['success']) {
            $response['redirect'] = route('transactions.show', $transaction->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.transactions', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('transactions.edit', $transaction->id);

            $message = $response['message'];

            flash($message)->error()->important();
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
    public function destroy(Transaction $transaction)
    {
        $response = $this->ajaxDispatch(new DeleteTransaction($transaction));

        $response['redirect'] = route('transactions.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.transactions', 1)]);

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
        return $this->exportExcel(new Export, trans_choice('general.transactions', 2));
    }

    /**
     * Download the PDF file of transaction.
     *
     * @param  Transaction $transaction
     *
     * @return Response
     */
    public function emailTransaction(Transaction $transaction)
    {
        if (empty($transaction->contact->email)) {
            return redirect()->back();
        }

        $response = $this->ajaxDispatch(new SendTransaction($transaction));

        if ($response['success']) {
            $message = trans('documents.messages.email_sent', ['type' => trans_choice('general.transactions', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return redirect()->back();
    }

    /**
     * Print the transaction.
     *
     * @param  Transaction $transaction
     *
     * @return Response
     */
    public function printTransaction(Transaction $transaction)
    {
        event(new TransactionPrinting($transaction));

        $real_type = $this->getRealTypeTransaction($transaction->type);

        $view = view('banking.transactions.print_default', compact('transaction', 'real_type'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Download the PDF file of transaction.
     *
     * @param  Transaction $transaction
     *
     * @return Response
     */
    public function pdfTransaction(Transaction $transaction)
    {
        event(new TransactionPrinting($transaction));

        $currency_style = true;

        $real_type = $this->getRealTypeTransaction($transaction->type);

        $view = view('banking.transactions.print_default', compact('transaction', 'currency_style', 'real_type'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = $this->getTransactionFileName($transaction);

        return $pdf->download($file_name);
    }

    public function dial(Transaction $transaction)
    {
        $documents = collect([]);

        if ($transaction->isIncome() && $transaction->contact->exists) {
            $builder = $transaction->contact->invoices();
        }

        if ($transaction->isIncome() && ! $transaction->contact->exists) {
            $builder = Document::invoice();
        }

        if ($transaction->isExpense() && $transaction->contact->exists) {
            $builder = $transaction->contact->bills();
        }

        if ($transaction->isExpense() && ! $transaction->contact->exists) {
            $builder = Document::bill();
        }

        if (isset($builder)) {
            $documents = $builder->notPaid()
                                ->where('currency_code', $transaction->currency_code)
                                ->with(['media', 'totals', 'transactions'])
                                ->get()
                                ->toJson();
        }

        $translations = collect($this->getTranslationsForConnect($transaction->type));

        $data = [
            'transaction' => $transaction->load(['account', 'category', 'taxes'])->toJson(),
            'currency' => $transaction->currency->toJson(),
            'documents' => $documents,
            'translations' => $translations->toJson(),
        ];

        return response()->json($data);
    }

    public function connect(Transaction $transaction, TransactionConnect $request)
    {
        $total_items = count($request->data['items']);

        if ($total_items == 1) {
            $document = Document::find($request->data['items'][0]['document_id']);

            if (!is_null($document)) {
                $response = $this->ajaxDispatch(new MatchBankingDocumentTransaction($document, $transaction));
            }
        }

        if ($total_items > 1) {
            $response = $this->ajaxDispatch(new SplitTransaction($transaction, $request->data));
        }

        $response['redirect'] = route('transactions.index');

        if ($response['success']) {
            $message = trans('messages.success.connected', ['type' => trans_choice('general.transactions', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
}

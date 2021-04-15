<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Exports\Banking\Transactions as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Banking\Transactions as Import;
use App\Jobs\Banking\DeleteTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Setting\Category;

class Transactions extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $types = collect(['expense' => trans_choice('general.expenses', 1), 'income' => trans_choice('general.incomes', 1)])
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.types', 2)]), '');

        $request_type = !request()->has('type') ? ['income', 'expense'] : request('type');
        $categories = Category::enabled()->type($request_type)->orderBy('name')->pluck('name', 'id');

        $transactions = Transaction::with('account', 'category', 'contact')->collect(['paid_at'=> 'desc']);

        return $this->response('banking.transactions.index', compact('transactions', 'accounts', 'types', 'categories'));
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
     * Remove the specified resource from storage.
     *
     * @param  Transaction $transaction
     *
     * @return Response
     */
    public function destroy(Transaction $transaction)
    {
        $response = $this->ajaxDispatch(new DeleteTransaction($transaction));

        $response['redirect'] = url()->previous();

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
}

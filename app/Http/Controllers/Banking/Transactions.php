<?php

namespace App\Http\Controllers\Banking;

use App\Http\Controllers\Controller;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Expense\BillPayment;
use App\Models\Expense\Payment;
use App\Models\Income\InvoicePayment;
use App\Models\Income\Revenue;
use App\Models\Setting\Category;

class Transactions extends Controller
{

    public $transactions = [];

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $request = request();
        
        $accounts = collect(Account::enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.accounts', 2)]), '');

        $types = collect(['expense' => 'Expense', 'income' => 'Income'])
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.types', 2)]), '');
            
        $categories = collect(Category::enabled()->type('income')->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.categories', 2)]), '');

        $type = $request->get('type');

        if ($type != 'income') {
            $this->addTransactions(Payment::collect('paid_at'), trans_choice('general.expenses', 1));
            $this->addTransactions(BillPayment::collect('paid_at'), trans_choice('general.expenses', 1), trans_choice('general.bills', 1));
        }

        if ($type != 'expense') {
            $this->addTransactions(Revenue::collect('paid_at'), trans_choice('general.incomes', 1));
            $this->addTransactions(InvoicePayment::collect('paid_at'), trans_choice('general.incomes', 1), trans_choice('general.invoices', 1));
        }

        $transactions = $this->getTransactions($request);

        return view('banking.transactions.index', compact('transactions', 'accounts', 'types', 'categories'));
    }

    /**
     * Add items to transactions array.
     *
     * @param $items
     * @param $type
     * @param $category
     */
    protected function addTransactions($items, $type, $category = null)
    {
        foreach ($items as $item) {
            $data = [
                'paid_at'           => $item->paid_at,
                'account_name'      => $item->account->name,
                'type'              => $type,
                'description'       => $item->description,
                'amount'            => $item->amount,
                'currency_code'     => $item->currency_code,
            ];

            if (!is_null($category)) {
                $data['category_name'] = $category;
            } else {
                $data['category_name'] = $item->category->name;
            }

            $this->transactions[] = (object) $data;
        }
    }

    protected function getTransactions($request)
    {
        // Sort items
        if (isset($request['sort'])) {
            if ($request['order'] == 'asc') {
                $f = 'sortBy';
            } else {
                $f = 'sortByDesc';
            }

            $transactions = collect($this->transactions)->$f($request['sort']);
        } else {
            $transactions = collect($this->transactions)->sortByDesc('paid_at');
        }

        return $transactions;
    }
}

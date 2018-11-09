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
        
        $accounts = collect(Account::enabled()->pluck('name', 'id'));

        $types = collect(['expense' => 'Expense', 'income' => 'Income'])
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.types', 2)]), '');

        $type = $request->get('type');

        $type_cats = empty($type) ? ['income', 'expense'] : $type;
        $categories = collect(Category::enabled()->type($type_cats)->pluck('name', 'id'));

        if ($type != 'income') {
            $this->addTransactions(Payment::collect(['paid_at'=> 'desc']), trans_choice('general.expenses', 1));
            $this->addTransactions(BillPayment::collect(['paid_at'=> 'desc']), trans_choice('general.expenses', 1));
        }

        if ($type != 'expense') {
            $this->addTransactions(Revenue::collect(['paid_at'=> 'desc']), trans_choice('general.incomes', 1));
            $this->addTransactions(InvoicePayment::collect(['paid_at'=> 'desc']), trans_choice('general.incomes', 1));
        }

        $transactions = $this->getTransactions($request);

        return view('banking.transactions.index', compact('transactions', 'accounts', 'types', 'categories'));
    }

    /**
     * Add items to transactions array.
     *
     * @param $items
     * @param $type
     */
    protected function addTransactions($items, $type)
    {
        foreach ($items as $item) {
            if (!empty($item->category)) {
                $category_name = ($item->category) ? $item->category->name : trans('general.na');
            } else {
                if ($type == trans_choice('general.incomes', 1)) {
                    $category_name = ($item->invoice->category) ? $item->invoice->category->name : trans('general.na');
                } else {
                    $category_name = ($item->bill->category) ? $item->bill->category->name : trans('general.na');
                }
            }

            $this->transactions[] = (object) [
                'paid_at'           => $item->paid_at,
                'account_name'      => $item->account->name,
                'type'              => $type,
                'description'       => $item->description,
                'amount'            => $item->amount,
                'currency_code'     => $item->currency_code,
                'category_name'     => $category_name,
            ];
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

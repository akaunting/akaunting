<?php

namespace App\Http\Controllers\Banking;

use App\Http\Controllers\Controller;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Expense\Payment;
use App\Models\Income\Revenue;
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
        $request = request();
        
        $transactions = array();
        
        $accounts = collect(Account::enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.accounts', 2)]), '');

        $types = collect(['expense' => 'Expense', 'income' => 'Income'])
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.types', 2)]), '');
            
        $categories = collect(Category::enabled()->type('income')->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.categories', 2)]), '');

        $type = $request->get('type');

        if ($type != 'income') {
            $payments = Payment::collect('paid_at');
    
            foreach ($payments as $payment) {
                $transactions[] = (object)[
                    'paid_at' => $payment->paid_at,
                    'account_name' => $payment->account->name,
                    'type' => trans_choice('general.expenses', 1),
                    'category_name' => $payment->category->name,
                    'description' => $payment->description,
                    'amount' => $payment->amount,
                    'currency_code' => $payment->currency_code,
                ];
            }
        }

        if ($type != 'expense') {
            $revenues = Revenue::collect('paid_at');

            foreach ($revenues as $revenue) {
                $transactions[] = (object)[
                    'paid_at' => $revenue->paid_at,
                    'account_name' => $revenue->account->name,
                    'type' => trans_choice('general.incomes', 1),
                    'category_name' => $revenue->category->name,
                    'description' => $revenue->description,
                    'amount' => $revenue->amount,
                    'currency_code' => $revenue->currency_code,
                ];
            }
        }

        $special_key = array(
            'account.name' => 'account_name',
            'category.name' => 'category_name',
        );

        if (isset($request['sort']) && array_key_exists($request['sort'], $special_key)) {
            $sort_order = array();

            foreach ($transactions as $key => $value) {
                $sort = $request['sort'];

                if (array_key_exists($request['sort'], $special_key)) {
                    $sort = $special_key[$request['sort']];
                }

                $sort_order[$key] = $value->{$sort};
            }

            $sort_type = (isset($request['order']) && $request['order'] == 'asc') ? SORT_ASC : SORT_DESC;

            array_multisort($sort_order, $sort_type, $transactions);
        }

        $transactions = (object) $transactions;

        return view('banking.transactions.index', compact('transactions', 'accounts', 'types', 'categories'));
    }
}

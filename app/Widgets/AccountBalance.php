<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Account;

class AccountBalance extends Widget
{
    public $default_name = 'widgets.account_balance';

    public $description = 'widgets.description.account_balance';

    public $report_class = 'App\Reports\IncomeExpense';

    public function show()
    {
        $this->setData();

        return $this->view('widgets.account_balance', $this->data);
    }

    public function setData(): void
    {
        // Use withSum instead of eager-loading all transactions to avoid
        // fetching millions of rows just to compute a per-account total.
        $accounts = Account::withSum('income_transactions as income_sum', 'amount')
            ->withSum('expense_transactions as expense_sum', 'amount')
            ->enabled()
            ->take(5)
            ->get()
            ->map(function ($account) {
                $balance = $account->opening_balance
                    + ($account->income_sum ?? 0)
                    - ($account->expense_sum ?? 0);

                $account->balance_formatted = money($balance, $account->currency_code);

                return $account;
            })->all();

        $this->data = [
            'accounts' => $accounts,
        ];
    }
}

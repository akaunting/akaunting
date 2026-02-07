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
        $accounts = Account::with('income_transactions', 'expense_transactions')->enabled()->take(5)->get()->map(function($account) {
            $account->balance_formatted = money($account->balance, $account->currency_code);

            return $account;
        })->all();

        $this->data = [
            'accounts' => $accounts,
        ];
    }
}

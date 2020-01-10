<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Account;

class AccountBalance extends Widget
{
    public function getDefaultName()
    {
        return trans('widgets.account_balance');
    }

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-4',
        ];
    }

    public function show()
    {
        $accounts = Account::enabled()->take(5)->get();

        return $this->view('widgets.account_balance', [
            'accounts' => $accounts,
        ]);
    }
}

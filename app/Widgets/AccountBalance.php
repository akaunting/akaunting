<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Account;

class AccountBalance extends Widget
{
    public function show()
    {
        $accounts = Account::enabled()->take(5)->get();

        return view('widgets.account_balance', [
            'config' => (object) $this->config,
            'accounts' => $accounts,
        ]);
    }
}

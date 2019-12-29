<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Account;

class AccountBalance extends Widget
{
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //
        $accounts = Account::enabled()->take(5)->get();

        return view('widgets.account_balance', [
            'config' => (object) $this->config,
            'accounts' => $accounts,
        ]);
    }
}

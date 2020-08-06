<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Utilities\Modules;

class Defaults extends Controller
{
    public function edit()
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('settings.default.edit', compact(
            'accounts',
            'currencies',
            'taxes',
            'payment_methods'
        ));
    }
}

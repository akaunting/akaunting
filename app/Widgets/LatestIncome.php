<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;

class LatestIncome extends Widget
{
    public function show()
    {
        $transactions = Transaction::with('category')->type('income')->orderBy('paid_at', 'desc')->isNotTransfer()->take(5)->get();

        return view('widgets.latest_income', [
            'config' => (object) $this->config,
            'transactions' => $transactions,
        ]);
    }
}
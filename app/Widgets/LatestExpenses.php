<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;

class LatestExpenses extends Widget
{
    public $default_name = 'widgets.latest_expenses';

    public function show()
    {
        $transactions = $this->applyFilters(Transaction::with('category')->expense()->orderBy('paid_at', 'desc')->isNotTransfer()->take(5))->get();

        return $this->view('widgets.latest_expenses', [
            'transactions' => $transactions,
        ]);
    }
}

<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;

class LatestExpenses extends Widget
{
    public function getDefaultName()
    {
        return trans('widgets.latest_expenses');
    }

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-4',
        ];
    }

    public function show()
    {
        $transactions = $this->applyFilters(Transaction::with('category')->type('expense')->orderBy('paid_at', 'desc')->isNotTransfer()->take(5))->get();

        return $this->view('widgets.latest_expenses', [
            'transactions' => $transactions,
        ]);
    }
}

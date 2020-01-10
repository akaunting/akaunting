<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;

class LatestIncome extends Widget
{
    public function getDefaultName()
    {
        return trans('widgets.latest_income');
    }

    public function getDefaultSettings()
    {
        return [
            'width' => 'col-md-4',
        ];
    }

    public function show()
    {
        $transactions = $this->applyFilters(Transaction::with('category')->type('income')->orderBy('paid_at', 'desc')->isNotTransfer()->take(5))->get();

        return $this->view('widgets.latest_income', [
            'transactions' => $transactions,
        ]);
    }
}

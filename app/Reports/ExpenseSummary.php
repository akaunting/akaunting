<?php

namespace App\Reports;

use App\Abstracts\Reports\Report;
use App\Models\Banking\Transaction;
use App\Models\Expense\Bill;
use App\Utilities\Recurring;

class ExpenseSummary extends Report
{
    public $category = 'income-expense';

    public $icon = 'fa fa-shopping-cart';

    public $chart = [
        'line' => [
            'width' => '0',
            'height' => '300',

            'options' => [

                'color' => '#ef3232'
            ],

            'backgroundColor' => '#ef3232',
            'color' => '#ef3232',
        ],
    ];

    public function getName()
    {
        return trans('reports.summary.expense');
    }

    public function getTotals()
    {
        $payments = $this->applyFilters(Transaction::type('expense')->isNotTransfer(), ['date_field' => 'paid_at'])->get();

        switch ($this->report->basis) {
            case 'cash':
                // Payments
                $this->setTotals($payments, 'paid_at');

                break;
            default:
                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'bill', 'billed_at');
                $this->setTotals($bills, 'billed_at');

                // Payments
                Recurring::reflect($payments, 'payment', 'paid_at');
                $this->setTotals($payments, 'paid_at');

                break;
        }
    }
}

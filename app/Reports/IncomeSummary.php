<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Sale\Invoice;
use App\Utilities\Recurring;

class IncomeSummary extends Report
{
    public $category = 'income-expense';

    public $icon = 'fa fa-money-bill';

    public $chart = [
        'line' => [
            'width' => '0',
            'height' => '300',

            'options' => [

                'color' => '#328aef'
            ],

            'backgroundColor' => '#328aef',
            'color' => '#328aef',
        ],
    ];


    public function getName()
    {
        return trans('reports.summary.income');
    }

    public function getTotals()
    {
        $transactions = $this->applyFilters(Transaction::type('income')->isNotTransfer(), ['date_field' => 'paid_at'])->get();

        switch ($this->report->basis) {
            case 'cash':
                // Transactions
                $this->setTotals($transactions, 'paid_at');

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoice', 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at');

                // Transactions
                Recurring::reflect($transactions, 'transaction', 'paid_at');
                $this->setTotals($transactions, 'paid_at');

                break;
        }
    }
}

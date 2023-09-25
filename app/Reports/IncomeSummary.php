<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Utilities\Recurring;

class IncomeSummary extends Report
{
    public $default_name = 'reports.income_summary';

    public $icon = 'payments';

    public $type = 'summary';

    public $chart = [
        'bar' => [
            'colors' => [
                '#8bb475',
            ],
        ],
        'donut' => [
            //
        ],
    ];

    public function setTables()
    {
        $this->tables = [
            'income' => trans_choice('general.incomes', 1),
        ];
    }

    public function setData()
    {
        $transactions = $this->applyFilters(Transaction::with('recurring')->income()->isNotTransfer(), ['date_field' => 'paid_at']);

        switch ($this->getBasis()) {
            case 'cash':
                // Incomes
                $incomes = $transactions->get();
                $this->setTotals($incomes, 'paid_at', false, 'income');

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Document::invoice()->with('recurring', 'transactions', 'items')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($invoices, 'issued_at');
                $this->setTotals($invoices, 'issued_at', false, 'income');

                // Incomes
                $incomes = $transactions->isNotDocument()->get();
                Recurring::reflect($incomes, 'paid_at');
                $this->setTotals($incomes, 'paid_at', false, 'income');

                break;
        }
    }
}

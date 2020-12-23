<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Utilities\Recurring;

class IncomeSummary extends Report
{
    public $default_name = 'reports.summary.income';

    public $icon = 'fa fa-money-bill';

    public $chart = [
        'line' => [
            'width' => '0',
            'height' => '300',
            'options' => [
                'color' => '#328aef',
                'legend' => [
                    'display' => false,
                ],
            ],
            'backgroundColor' => '#328aef',
            'color' => '#328aef',
        ],
    ];

    public function setData()
    {
        $transactions = $this->applyFilters(Transaction::with('recurring')->income()->isNotTransfer(), ['date_field' => 'paid_at']);

        switch ($this->getSetting('basis')) {
            case 'cash':
                // Revenues
                $revenues = $transactions->get();
                $this->setTotals($revenues, 'paid_at');

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Document::invoice()->with('recurring', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($invoices, 'issued_at');
                $this->setTotals($invoices, 'issued_at');

                // Revenues
                $revenues = $transactions->isNotDocument()->get();
                Recurring::reflect($revenues, 'paid_at');
                $this->setTotals($revenues, 'paid_at');

                break;
        }
    }
}

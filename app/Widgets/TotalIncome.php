<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;

class TotalIncome extends Widget
{
    public $default_name = 'widgets.total_income';

    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function show()
    {
        $current = $open = $overdue = 0;

        $this->applyFilters(Transaction::income()->isNotTransfer())->each(function ($transaction) use (&$current) {
            $current += $transaction->getAmountConvertedToDefault();
        });

        $this->applyFilters(
            Document::invoice()->with('transactions')->accrued()->notPaid(),
            ['date_field' => 'created_at']
        )->each(
            function ($invoice) use (&$open, &$overdue) {
                list($open_tmp, $overdue_tmp) = $this->calculateDocumentTotals($invoice);

                $open += $open_tmp;
                $overdue += $overdue_tmp;
            }
        );

        $grand = $current + $open + $overdue;

        $progress = 100;

        if (!empty($open) && !empty($overdue)) {
            $progress = (int) ($open * 100) / ($open + $overdue);
        }

        $totals = [
            'grand'         => money($grand, setting('default.currency'), true),
            'open'          => money($open, setting('default.currency'), true),
            'overdue'       => money($overdue, setting('default.currency'), true),
            'progress'      => $progress,
        ];

        return $this->view('widgets.total_income', [
            'totals' => $totals,
        ]);
    }
}

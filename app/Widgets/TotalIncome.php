<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use App\Models\Sale\Invoice;

class TotalIncome extends Widget
{
    public function show()
    {
        $current = $open = $overdue = 0;

        $this->applyFilters(Transaction::type('income')->isNotTransfer())->each(function ($transaction) use (&$current) {
            $current += $transaction->getAmountConvertedToDefault();
        });

        $this->applyFilters(Invoice::accrued()->notPaid(), ['date_field' => 'created_at'])->each(function ($invoice) use (&$open, &$overdue) {
            list($open_tmp, $overdue_tmp) = $this->calculateDocumentTotals($invoice);

            $open += $open_tmp;
            $overdue += $overdue_tmp;
        });

        $progress = 100;

        if (!empty($open) && !empty($overdue)) {
            $progress = (int) ($open * 100) / ($open + $overdue);
        }

        $totals = [
            'current'       => $current,
            'open'          => money($open, setting('default.currency'), true),
            'overdue'       => money($overdue, setting('default.currency'), true),
            'progress'      => $progress,
        ];

        return $this->view('widgets.total_income', [
            'totals' => $totals,
        ]);
    }

    public function getDefaultName()
    {
        return trans('widgets.total_income');
    }
}

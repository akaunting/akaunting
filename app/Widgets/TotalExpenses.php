<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Transaction;
use App\Models\Expense\Bill;

class TotalExpenses extends Widget
{
    public function show()
    {
        $current = $open = $overdue = 0;

        Transaction::type('expense')->isNotTransfer()->each(function ($transaction) use (&$current) {
            $current += $transaction->getAmountConvertedToDefault();
        });

        Bill::accrued()->notPaid()->each(function ($bill) use (&$open, &$overdue) {
            list($open_tmp, $overdue_tmp) = $this->calculateDocumentTotals($bill);

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

        return view('widgets.total_expenses', [
            'config' => (object) $this->config,
            'totals' => $totals,
        ]);
    }
}

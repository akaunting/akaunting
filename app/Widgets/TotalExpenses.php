<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Setting\Category;
use Date;

class TotalExpenses extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'width' => 'col-md-4'
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //
        $expenses_amount = $open_bill = $overdue_bill = 0;

        // Get categories
        $categories = Category::with(['bills', 'payments'])->type(['expense'])->enabled()->get();

        foreach ($categories as $category) {
            $amount = 0;

            // Payments
            foreach ($category->payments as $payment) {
                $amount += $payment->getAmountConvertedToDefault();
            }

            $expenses_amount += $amount;

            // Bills
            $bills = $category->bills()->accrued()->get();
            foreach ($bills as $bill) {
                list($open, $overdue) = $this->calculateInvoiceBillTotals($bill, 'bill');

                $open_bill += $open;
                $overdue_bill += $overdue;
            }
        }

        $expenses_progress = 100;

        if (!empty($open_bill) && !empty($overdue_bill)) {
            $expenses_progress = (int) ($open_bill * 100) / ($open_bill + $overdue_bill);
        }

        $total_expenses = array(
            'total'         => $expenses_amount,
            'open_bill'     => money($open_bill, setting('default.currency'), true),
            'overdue_bill'  => money($overdue_bill, setting('default.currency'), true),
            'progress'      => $expenses_progress
        );

        return view('widgets.total_expenses', [
            'config' => (object) $this->config,
            'total_expenses' => $total_expenses,
        ]);
    }

    private function calculateInvoiceBillTotals($item, $type)
    {
        $open = $overdue = 0;

        $today = Date::today()->toDateString();

        $code_field = $type . '_status_code';

        if ($item->$code_field != 'paid') {
            $payments = 0;

            if ($item->$code_field == 'partial') {
                foreach ($item->transactions as $transaction) {
                    $payments += $transaction->getAmountConvertedToDefault();
                }
            }

            // Check if it's open or overdue invoice
            if ($item->due_at > $today) {
                $open += $item->getAmountConvertedToDefault() - $payments;
            } else {
                $overdue += $item->getAmountConvertedToDefault() - $payments;
            }
        }

        return [$open, $overdue];
    }
}

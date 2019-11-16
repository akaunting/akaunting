<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Setting\Category;
use Date;

class TotalProfit extends AbstractWidget
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
        list($incomes_amount, $open_invoice, $overdue_invoice, $expenses_amount, $open_bill, $overdue_bill) = $this->calculateAmounts();

        $incomes_progress = 100;

        if (!empty($open_invoice) && !empty($overdue_invoice)) {
            $incomes_progress = (int) ($open_invoice * 100) / ($open_invoice + $overdue_invoice);
        }

        // Totals
        $total_incomes = array(
            'total'             => $incomes_amount,
            'open_invoice'      => money($open_invoice, setting('default.currency'), true),
            'overdue_invoice'   => money($overdue_invoice, setting('default.currency'), true),
            'progress'          => $incomes_progress
        );

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

        $amount_profit = $incomes_amount - $expenses_amount;
        $open_profit = $open_invoice - $open_bill;
        $overdue_profit = $overdue_invoice - $overdue_bill;

        $total_progress = 100;

        if (!empty($open_profit) && !empty($overdue_profit)) {
            $total_progress = (int) ($open_profit * 100) / ($open_profit + $overdue_profit);
        }

        $total_profit = array(
            'total'         => $amount_profit,
            'open'          => money($open_profit, setting('default.currency'), true),
            'overdue'       => money($overdue_profit, setting('default.currency'), true),
            'progress'      => $total_progress
        );

        return view('widgets.total_profit', [
            'config' => (object) $this->config,
            'total_profit' => $total_profit,
        ]);
    }

    private function calculateAmounts()
    {
        $incomes_amount = $open_invoice = $overdue_invoice = 0;
        $expenses_amount = $open_bill = $overdue_bill = 0;

        // Get categories
        $categories = Category::with(['bills', 'invoices', 'payments', 'revenues'])->type(['income', 'expense'])->enabled()->get();

        foreach ($categories as $category) {
            switch ($category->type) {
                case 'income':
                    $amount = 0;

                    // Revenues
                    foreach ($category->revenues as $revenue) {
                        $amount += $revenue->getAmountConvertedToDefault();
                    }

                    $incomes_amount += $amount;

                    // Invoices
                    $invoices = $category->invoices()->accrued()->get();
                    foreach ($invoices as $invoice) {
                        list($open, $overdue) = $this->calculateInvoiceBillTotals($invoice, 'invoice');

                        $open_invoice += $open;
                        $overdue_invoice += $overdue;
                    }

                    break;
                case 'expense':
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

                    break;
            }
        }

        return array($incomes_amount, $open_invoice, $overdue_invoice, $expenses_amount, $open_bill, $overdue_bill);
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

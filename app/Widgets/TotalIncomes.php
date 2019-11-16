<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Setting\Category;
use Date;

class TotalIncomes extends AbstractWidget
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
        $incomes_amount = $open_invoice = $overdue_invoice = 0;

        // Get categories
        $categories = Category::with(['invoices', 'revenues'])->type(['income'])->enabled()->get();

        foreach ($categories as $category) {
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
        }

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

        return view('widgets.total_incomes', [
            'config' => (object) $this->config,
            'total_incomes' => $total_incomes,
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

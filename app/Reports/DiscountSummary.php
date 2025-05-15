<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Document\Document;
use App\Utilities\Recurring;
use App\Utilities\Date;
use App\Traits\Currencies;
use App\Events\Report\TotalCalculating;
use App\Events\Report\TotalCalculated;

class DiscountSummary extends Report
{
    use Currencies;

    public $default_name = 'reports.discount_summary';

    public $icon = 'sell';

    public $type = 'summary';

    public $chart = [
        'income' => [
            'bar' => [
                'colors' => [
                    '#8bb475',
                ],
            ],
            'donut' => [
                //
            ],
        ],
        'expense' => [
            'bar' => [
                'colors' => [
                    '#fb7185',
                ],
            ],
            'donut' => [
                //
            ],
        ],
    ];

    public function setTables()
    {
        $this->tables = [
            'income' => trans_choice('general.incomes', 1),
            'expense' => trans_choice('general.expenses', 2),
        ];
    }

    public function setData()
    {
        $invoices = $this->applyFilters(Document::invoice()->with('totals')->accrued(), ['date_field' => 'issued_at'])->get();
        Recurring::reflect($invoices, 'issued_at');
        $this->setTotals($invoices, 'issued_at', false, 'income');

        // Bills
        $bills = $this->applyFilters(Document::bill()->with('totals')->accrued(), ['date_field' => 'issued_at'])->get();
        Recurring::reflect($bills, 'issued_at');
        $this->setTotals($bills, 'issued_at', false, 'expense');
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default', $with_tax = true)
    {
        event(new TotalCalculating($this, $items, $date_field, $check_type, $table, $with_tax));

        $group_field = $this->getSetting('group') . '_id';

        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $date = $this->getFormattedDate(Date::parse($item->$date_field));

            if (!isset($item->$group_field)) {
                continue;
            }

            $group = $item->$group_field;

            $totals = $item->totals;

            foreach ($totals as $total) {
                if (! in_array($total->code, ['item_discount', 'discount'])) {
                    continue;
                }

                if (
                    !isset($this->row_values[$table][$group])
                    || !isset($this->row_values[$table][$group][$date])
                    || !isset($this->footer_totals[$table][$date])
                ) {
                    continue;
                }

                $amount = $this->convertToDefault($total->amount, $item->currency_code, $item->currency_rate);

                $type = ($item->type === Document::INVOICE_TYPE || $item->type === 'income') ? 'income' : 'expense';

                if (($check_type == false) || ($type == 'income')) {
                    $this->row_values[$table][$group][$date] += $amount;
    
                    $this->footer_totals[$table][$date] += $amount;
                } else {
                    $this->row_values[$table][$group][$date] -= $amount;
    
                    $this->footer_totals[$table][$date] -= $amount;
                }
            }
        }

        event(new TotalCalculated($this, $items, $date_field, $check_type, $table, $with_tax));
    }

    public function getFields()
    {
        return [
            $this->getGroupField(),
            $this->getPeriodField(),
        ];
    }
}

<?php

namespace App\Reports;

use App\Abstracts\Reports\Report;
use App\Models\Banking\Transaction;
use App\Models\Expense\Bill;
use App\Models\Income\Invoice;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Utilities\Recurring;
use Date;

class TaxSummary extends Report
{
    use Currencies;

    public $category = 'accounting';

    public $icon = 'fa fa-percent';

    public $chart = false;

    public function getName()
    {
        return trans('reports.summary.tax');
    }

    public function setViews()
    {
        parent::setViews();
        $this->views['content.header'] = 'reports.tax_summary.content.header';
        $this->views['table.header'] = 'reports.tax_summary.table.header';
        $this->views['table.footer'] = 'reports.tax_summary.table.footer';
    }

    public function setTables()
    {
        $taxes = Tax::enabled()->where('rate', '<>', '0')->orderBy('name')->pluck('name')->toArray();

        $this->tables = array_combine($taxes, $taxes);
    }

    public function getTableRowList()
    {
        return [
            'income' => trans_choice('general.incomes', 2),
            'expense' => trans_choice('general.expenses', 2),
        ];
    }

    public function getTotals()
    {
        switch ($this->report->basis) {
            case 'cash':
                // Invoice Payments
                $invoices = $this->applyFilters(Transaction::type('income')->isDocument()->with(['invoice', 'invoice.totals'])->isNotTransfer(), ['date_field' => 'paid_at'])->get();
                $this->setTotals($invoices, 'paid_at');

                // Bill Payments
                $bills = $this->applyFilters(Transaction::type('expense')->isDocument()->with(['bill', 'bill.totals'])->isNotTransfer(), ['date_field' => 'paid_at'])->get();
                $this->setTotals($bills, 'paid_at');

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoice', 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at');

                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'bill', 'billed_at');
                $this->setTotals($bills, 'billed_at');

                break;
        }
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default')
    {
        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $db_table = $item->getTable();

            $date = $this->getFormattedDate(Date::parse($item->$date_field));

            if ($date_field == 'paid_at') {
                $document_type = ($item->type == 'income') ? 'invoice' : 'bill';
                $item_totals = $item->$document_type->totals;
                $type_item = $item->$document_type;
            } else {
                $item_totals = $item->totals;
            }

            foreach ($item_totals as $item_total) {
                if ($item_total->code != 'tax') {
                    continue;
                }

                $type = (($db_table == 'invoices') || (($db_table == 'transactions') && ($item->type == 'income'))) ? 'income' : 'expense';

                if (!isset($this->rows[$item_total->name][$type][$date]) ||
                    !isset($this->totals[$item_total->name][$date]))
                {
                    continue;
                }

                if ($date_field == 'paid_at') {
                    $rate = ($item->amount * 100) / $type_item->amount;
                    $item_amount = ($item_total->amount / 100) * $rate;
                } else {
                    $item_amount = $item_total->amount;
                }

                $amount = $this->convertToDefault($item_amount, $item->currency_code, $item->currency_rate);

                if ($type == 'income') {
                    $this->rows[$item_total->name][$type][$date] += $amount;

                    $this->totals[$item_total->name][$date] += $amount;
                } else {
                    $this->rows[$item_total->name][$type][$date] -= $amount;

                    $this->totals[$item_total->name][$date] -= $amount;
                }
            }
        }
    }
}

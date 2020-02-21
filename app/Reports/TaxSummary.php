<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Utilities\Recurring;
use Date;

class TaxSummary extends Report
{
    use Currencies;

    public $default_name = 'reports.summary.tax';

    public $category = 'general.accounting';

    public $icon = 'fa fa-percent';

    public $indents = [
        'table_header' => '0px',
        'table_rows' => '48px',
    ];

    public function setViews()
    {
        parent::setViews();
        $this->views['content.header'] = 'reports.tax_summary.content.header';
        $this->views['content.footer'] = 'reports.tax_summary.content.footer';
        $this->views['table.header'] = 'reports.tax_summary.table.header';
        $this->views['table.footer'] = 'reports.tax_summary.table.footer';
    }

    public function setTables()
    {
        $taxes = Tax::enabled()->where('rate', '<>', '0')->orderBy('name')->pluck('name')->toArray();

        $this->tables = array_combine($taxes, $taxes);
    }

    public function setData()
    {
        switch ($this->model->settings->basis) {
            case 'cash':
                // Invoice Payments
                $invoices = $this->applyFilters(Transaction::with(['invoice', 'invoice.totals'])->type('income')->isDocument()->isNotTransfer(), ['date_field' => 'paid_at'])->get();
                $this->setTotals($invoices, 'paid_at');

                // Bill Payments
                $bills = $this->applyFilters(Transaction::with(['bill', 'bill.totals'])->type('expense')->isDocument()->isNotTransfer(), ['date_field' => 'paid_at'])->get();
                $this->setTotals($bills, 'paid_at');

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at');

                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'billed_at');
                $this->setTotals($bills, 'billed_at');

                break;
        }
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default')
    {
        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $type = (($item instanceof Invoice) || (($item instanceof Transaction) && ($item->type == 'income'))) ? 'income' : 'expense';

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

                if (
                    !isset($this->row_values[$item_total->name][$type][$date])
                    || !isset($this->footer_totals[$item_total->name][$date])
                ) {
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
                    $this->row_values[$item_total->name][$type][$date] += $amount;

                    $this->footer_totals[$item_total->name][$date] += $amount;
                } else {
                    $this->row_values[$item_total->name][$type][$date] -= $amount;

                    $this->footer_totals[$item_total->name][$date] -= $amount;
                }
            }
        }
    }

    public function getFields()
    {
        return [
            $this->getPeriodField(),
            $this->getBasisField(),
        ];
    }
}

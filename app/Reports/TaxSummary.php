<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
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
        $withholding = ($this->getSetting('withholding') == 'yes') ? 'withholding' : 'notWithholding';

        $taxes = Tax::enabled()->$withholding()->notRate(0)->orderBy('name')->pluck('name')->toArray();

        $this->tables = array_combine($taxes, $taxes);
    }

    public function setData()
    {
        switch ($this->getSetting('basis')) {
            case 'cash':
                // Invoice Payments
                $invoices = $this->applyFilters(Transaction::with('recurring', 'invoice', 'invoice.totals')->income()->isDocument()->isNotTransfer(), ['date_field' => 'paid_at'])->get();
                $this->setTotals($invoices, 'paid_at');

                // Bill Payments
                $bills = $this->applyFilters(Transaction::with('recurring', 'bill', 'bill.totals')->expense()->isDocument()->isNotTransfer(), ['date_field' => 'paid_at'])->get();
                $this->setTotals($bills, 'paid_at');

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Document::invoice()->with('recurring', 'totals', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($invoices, 'issued_at');
                $this->setTotals($invoices, 'issued_at');

                // Bills
                $bills = $this->applyFilters(Document::bill()->with('recurring', 'totals', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($bills, 'issued_at');
                $this->setTotals($bills, 'issued_at');

                break;
        }
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default', $with_tax = true)
    {
        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $type = ($item->type === Document::INVOICE_TYPE || $item->type === 'income') ? 'income' : 'expense';

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
            $this->getWithholdingField(),
        ];
    }

    public function getWithholdingField()
    {
        return [
            'type' => 'selectGroup',
            'name' => 'withholding',
            'title' => trans('taxes.withholding'),
            'icon' => 'hand-holding-usd',
            'values' => [
                'yes' => trans('general.yes'),
                'no' => trans('general.no'),
            ],
            'selected' => 'no',
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }
}

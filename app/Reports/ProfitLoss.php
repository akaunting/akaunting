<?php

namespace App\Reports;

use App\Abstracts\Reports\Report;
use App\Models\Banking\Transaction;
use App\Models\Expense\Bill;
use App\Models\Income\Invoice;
use App\Models\Setting\Category;
use App\Utilities\Recurring;

class ProfitLoss extends Report
{
    public $category = 'accounting';

    public $icon = 'fa fa-heart';

    public $chart = false;

    public function getName()
    {
        return trans('reports.profit_loss');
    }

    public function setViews()
    {
        parent::setViews();
        $this->views['content.header'] = 'reports.profit_loss.content.header';
        $this->views['content.footer'] = 'reports.profit_loss.content.footer';
        $this->views['table.header'] = 'reports.profit_loss.table.header';
        $this->views['table.footer'] = 'reports.profit_loss.table.footer';
    }

    public function setTables()
    {
        $this->tables = [
            'income' => trans_choice('general.incomes', 1),
            'expense' => trans_choice('general.expenses', 2),
        ];
    }

    public function getTableRowList()
    {
        $this->cat_list = Category::type(['income', 'expense'])->enabled()->orderBy('name')->get();

        return collect($this->cat_list)->pluck('name', 'id')->toArray();
    }

    public function setRows()
    {
        $list = $this->getTableRowList();

        foreach ($this->dates as $date) {
            foreach ($this->tables as $t_id => $t_name) {
                foreach ($list as $id => $name) {
                    $cat = $this->cat_list->where('id', $id)->first();

                    if ($cat->type != $t_id) {
                        continue;
                    }

                    $this->rows[$t_name][$id][$date] = 0;
                }
            }
        }
    }

    public function getTotals()
    {
        $revenues = $this->applyFilters(Transaction::type('income')->isNotTransfer(), ['date_field' => 'paid_at'])->get();
        $payments = $this->applyFilters(Transaction::type('expense')->isNotTransfer(), ['date_field' => 'paid_at'])->get();

        switch ($this->report->basis) {
            case 'cash':
                // Revenues
                $this->setTotals($revenues, 'paid_at', true, $this->tables['income']);

                // Payments
                $this->setTotals($payments, 'paid_at', true, $this->tables['expense']);

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoice', 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at', true, $this->tables['income']);

                // Revenues
                Recurring::reflect($revenues, 'revenue', 'paid_at');
                $this->setTotals($revenues, 'paid_at', true, $this->tables['income']);

                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'bill', 'billed_at');
                $this->setTotals($bills, 'billed_at', true, $this->tables['expense']);

                // Payments
                Recurring::reflect($payments, 'payment', 'paid_at');
                $this->setTotals($payments, 'paid_at', true, $this->tables['expense']);

                break;
        }

        // TODO: move to views
        foreach ($this->totals as $table => $dates) {
            foreach ($dates as $date => $total) {
                if (!isset($this->net_profit[$date])) {
                    $this->net_profit[$date] = 0;
                }

                $this->net_profit[$date] += $total;
            }
        }
    }
}

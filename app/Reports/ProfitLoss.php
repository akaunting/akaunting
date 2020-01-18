<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Models\Setting\Category;
use App\Utilities\Recurring;

class ProfitLoss extends Report
{
    public $default_name = 'reports.profit_loss';

    public $category = 'general.accounting';

    public $icon = 'fa fa-heart';

    public $chart = false;

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
        $income_transactions = $this->applyFilters(Transaction::type('income')->isNotTransfer(), ['date_field' => 'paid_at'])->get();
        $expense_transactions = $this->applyFilters(Transaction::type('expense')->isNotTransfer(), ['date_field' => 'paid_at'])->get();

        switch ($this->model->settings->basis) {
            case 'cash':
                // Income Transactions
                $this->setTotals($income_transactions, 'paid_at', true, $this->tables['income']);

                // Expense Transactions
                $this->setTotals($expense_transactions, 'paid_at', true, $this->tables['expense']);

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at', true, $this->tables['income']);

                // Income Transactions
                Recurring::reflect($income_transactions, 'paid_at');
                $this->setTotals($income_transactions, 'paid_at', true, $this->tables['income']);

                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'bill', 'billed_at');
                $this->setTotals($bills, 'billed_at', true, $this->tables['expense']);

                // Expense Transactions
                Recurring::reflect($expense_transactions, 'paid_at');
                $this->setTotals($expense_transactions, 'paid_at', true, $this->tables['expense']);

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

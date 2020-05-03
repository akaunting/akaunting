<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use App\Utilities\Recurring;

class ProfitLoss extends Report
{
    public $default_name = 'reports.profit_loss';

    public $category = 'general.accounting';

    public $icon = 'fa fa-heart';

    public $indents = [
        'table_header' => '0px',
        'table_rows' => '48px',
    ];

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

    public function setData()
    {
        $income_transactions = $this->applyFilters(Transaction::income()->isNotTransfer(), ['date_field' => 'paid_at']);
        $expense_transactions = $this->applyFilters(Transaction::expense()->isNotTransfer(), ['date_field' => 'paid_at']);

        switch ($this->model->settings->basis) {
            case 'cash':
                // Revenues
                $revenues = $income_transactions->get();
                $this->setTotals($revenues, 'paid_at', true, $this->tables['income']);

                // Payments
                $payments = $expense_transactions->get();
                $this->setTotals($payments, 'paid_at', true, $this->tables['expense']);

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Invoice::accrued(), ['date_field' => 'invoiced_at'])->get();
                Recurring::reflect($invoices, 'invoiced_at');
                $this->setTotals($invoices, 'invoiced_at', true, $this->tables['income']);

                // Revenues
                $revenues = $income_transactions->isNotDocument()->get();
                Recurring::reflect($revenues, 'paid_at');
                $this->setTotals($revenues, 'paid_at', true, $this->tables['income']);

                // Bills
                $bills = $this->applyFilters(Bill::accrued(), ['date_field' => 'billed_at'])->get();
                Recurring::reflect($bills, 'bill', 'billed_at');
                $this->setTotals($bills, 'billed_at', true, $this->tables['expense']);

                // Payments
                $payments = $expense_transactions->isNotDocument()->get();
                Recurring::reflect($payments, 'paid_at');
                $this->setTotals($payments, 'paid_at', true, $this->tables['expense']);

                break;
        }

        // TODO: move to views
        foreach ($this->footer_totals as $table => $dates) {
            foreach ($dates as $date => $total) {
                if (!isset($this->net_profit[$date])) {
                    $this->net_profit[$date] = 0;
                }

                $this->net_profit[$date] += $total;
            }
        }
    }

    public function getFields()
    {
        return [
            $this->getGroupField(),
            $this->getPeriodField(),
            $this->getBasisField(),
        ];
    }
}

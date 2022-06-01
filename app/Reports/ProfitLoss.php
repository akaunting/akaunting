<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Utilities\Recurring;

class ProfitLoss extends Report
{
    public $default_name = 'reports.profit_loss';

    public $category = 'general.accounting';

    public $icon = 'favorite_border';

    public $type = 'detail';

    public $chart = false;

    public function setViews()
    {
        parent::setViews();
        $this->views['detail.content.header'] = 'reports.profit_loss.content.header';
        $this->views['detail.content.footer'] = 'reports.profit_loss.content.footer';
        $this->views['detail.table.header'] = 'reports.profit_loss.table.header';
        $this->views['detail.table.footer'] = 'reports.profit_loss.table.footer';
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
        $income_transactions = $this->applyFilters(Transaction::with('recurring')->income()->isNotTransfer(), ['date_field' => 'paid_at']);
        $expense_transactions = $this->applyFilters(Transaction::with('recurring')->expense()->isNotTransfer(), ['date_field' => 'paid_at']);

        switch ($this->getBasis()) {
            case 'cash':
                // Incomes
                $incomes = $income_transactions->get();
                $this->setTotals($incomes, 'paid_at', false, 'income', false);

                // Expenses
                $expenses = $expense_transactions->get();
                $this->setTotals($expenses, 'paid_at', false, 'expense', false);

                break;
            default:
                // Invoices
                $invoices = $this->applyFilters(Document::invoice()->with('recurring', 'totals', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($invoices, 'issued_at');
                $this->setTotals($invoices, 'issued_at', false, 'income', false);

                // Incomes
                $incomes = $income_transactions->isNotDocument()->get();
                Recurring::reflect($incomes, 'paid_at');
                $this->setTotals($incomes, 'paid_at', false, 'income', false);

                // Bills
                $bills = $this->applyFilters(Document::bill()->with('recurring', 'totals', 'transactions')->accrued(), ['date_field' => 'issued_at'])->get();
                Recurring::reflect($bills, 'issued_at');
                $this->setTotals($bills, 'issued_at', false, 'expense', false);

                // Expenses
                $expenses = $expense_transactions->isNotDocument()->get();
                Recurring::reflect($expenses, 'paid_at');
                $this->setTotals($expenses, 'paid_at', false, 'expense', false);

                break;
        }

        $this->setNetProfit();
    }

    public function setNetProfit()
    {
        foreach ($this->footer_totals as $table => $dates) {
            foreach ($dates as $date => $total) {
                if (!isset($this->net_profit[$date])) {
                    $this->net_profit[$date] = 0;
                }

                if ($table == 'income') {
                    $this->net_profit[$date] += $total;

                    continue;
                }

                $this->net_profit[$date] -= $total;
            }
        }
    }
}

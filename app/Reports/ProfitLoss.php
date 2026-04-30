<?php

namespace App\Reports;

use App\Abstracts\Report;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Utilities\Date;
use App\Utilities\Recurring;
use Illuminate\Database\Eloquent\Builder;

class ProfitLoss extends Report
{
    public $default_name = 'reports.profit_loss';

    public $category = 'general.accounting';

    public $icon = 'favorite_border';

    public $type = 'detail';

    public $chart = false;

    public $gross_profit = [];

    public $net_profit = [];

    public function setViews()
    {
        parent::setViews();
        $this->views['detail'] = 'reports.profit_loss.detail';
        $this->views['detail.content.header'] = 'reports.profit_loss.content.header';
        $this->views['detail.content.footer'] = 'reports.profit_loss.content.footer';
        $this->views['detail.table.header'] = 'reports.profit_loss.table.header';
        $this->views['detail.table.row'] = 'reports.profit_loss.table.row';
        $this->views['detail.table.footer'] = 'reports.profit_loss.table.footer';
    }

    public function setTables()
    {
        $this->tables = [
            Category::INCOME_TYPE  => trans_choice('general.incomes', 1),
            Category::COGS_TYPE    => trans_choice('general.cogs', 2),
            Category::EXPENSE_TYPE => trans_choice('general.expenses', 2),
        ];
    }

    public function setData()
    {
        switch ($this->getBasis()) {
            case 'cash':
                $incomes_query = $this->getTransactionQuery(Transaction::INCOME_TYPE);
                $expenses_query = $this->getTransactionQuery(Transaction::EXPENSE_TYPE);

                // Incomes
                $incomes = $incomes_query->get();
                $this->setTotals($incomes, 'paid_at', false, Category::INCOME_TYPE, false);

                // COGS: Expenses
                $cogs_expenses_query = clone $expenses_query; // To avoid affecting the original query used for non-COGS expenses
                $cogs_expenses = $cogs_expenses_query->whereHas('category', fn (Builder $q) => $q->cogs())->get();
                $this->setTotals($cogs_expenses, 'paid_at', false, Category::COGS_TYPE, false);

                // Expenses
                $expenses = $expenses_query->whereDoesntHave('category', fn (Builder $q) => $q->cogs())->get();
                $this->setTotals($expenses, 'paid_at', false, Category::EXPENSE_TYPE, false);

                break;
            default:
                $incomes_query = $this->getTransactionQuery(Transaction::INCOME_TYPE);
                $expenses_query = $this->getTransactionQuery(Transaction::EXPENSE_TYPE);
                $invoices_query = $this->getDocumentQuery(Document::INVOICE_TYPE);
                $bills_query = $this->getDocumentQuery(Document::BILL_TYPE);

                // Invoices
                $invoices = $invoices_query->get();
                Recurring::reflect($invoices, 'issued_at');
                $this->setTotals($invoices, 'issued_at', false, Category::INCOME_TYPE, false);

                // Incomes
                $incomes = $incomes_query->isNotDocument()->get();
                Recurring::reflect($incomes, 'paid_at');
                $this->setTotals($incomes, 'paid_at', false, Category::INCOME_TYPE, false);

                // COGS: Bills
                $cogs_bills_query = clone $bills_query; // To avoid affecting the original query used for non-COGS bills
                $cogs_bills = $cogs_bills_query->whereHas('category', fn (Builder $q) => $q->cogs())->get();
                Recurring::reflect($cogs_bills, 'issued_at');
                $this->setTotals($cogs_bills, 'issued_at', false, Category::COGS_TYPE, false);

                // COGS: Expenses
                $cogs_expenses_query = clone $expenses_query; // To avoid affecting the original query used for non-COGS expenses
                $cogs_expenses = $cogs_expenses_query->whereHas('category', fn (Builder $q) => $q->cogs())->get();
                Recurring::reflect($cogs_expenses, 'paid_at');
                $this->setTotals($cogs_expenses, 'paid_at', false, Category::COGS_TYPE, false);

                // Bills
                $bills = $bills_query->whereDoesntHave('category', fn (Builder $q) => $q->cogs())->get();
                Recurring::reflect($bills, 'issued_at');
                $this->setTotals($bills, 'issued_at', false, Category::EXPENSE_TYPE, false);

                // Expenses
                $expenses = $expenses_query->whereDoesntHave('category', fn (Builder $q) => $q->cogs())->get();
                Recurring::reflect($expenses, 'paid_at');
                $this->setTotals($expenses, 'paid_at', false, Category::EXPENSE_TYPE, false);

                break;
        }

        $this->setGrossProfit();
        $this->setNetProfit();
    }

    public function getTransactionQuery(string $type): Builder
    {
        return $this->applyFilters(
            model: Transaction::with('recurring')->$type()->isNotTransfer(),
            args: ['date_field' => 'paid_at', 'model_type' => $type],
        );
    }

    public function getDocumentQuery(string $type): Builder
    {
        return $this->applyFilters(
            model: Document::with('recurring', 'totals', 'transactions', 'items')->$type()->accrued(),
            args: ['date_field' => 'issued_at', 'model_type' => $type],
        );
    }

    public function setGrossProfit(): void
    {
        foreach ($this->dates as $date) {
            $income = $this->footer_totals[Category::INCOME_TYPE][$date] ?? 0;
            $cogs = $this->footer_totals[Category::COGS_TYPE][$date] ?? 0;

            $this->gross_profit[$date] = $income - $cogs;
        }
    }

    public function setNetProfit(): void
    {
        foreach ($this->footer_totals as $table => $dates) {
            if (! in_array($table, [Category::INCOME_TYPE, Category::EXPENSE_TYPE])) {
                continue;
            }

            foreach ($dates as $date => $total) {
                if (! isset($this->net_profit[$date])) {
                    $this->net_profit[$date] = 0;
                }

                if ($table == Category::INCOME_TYPE) {
                    $this->net_profit[$date] += $total;

                    continue;
                }

                $this->net_profit[$date] -= $total;
            }
        }
    }

    public function array(): array
    {
        $data = parent::array();

        $net_profit = $this->net_profit;
        $gross_profit = $this->gross_profit;

        if ($this->has_money) {
            $net_profit = array_map(fn ($value) => money($value)->format(), $net_profit);
            $gross_profit = array_map(fn ($value) => money($value)->format(), $gross_profit);
        }

        $data['gross_profit'] = $gross_profit;
        $data['net_profit'] = $net_profit;

        return $data;
    }

    public function getFields(): array
    {
        return [
            $this->getGroupField(),
            $this->getPeriodField(),
            $this->getBasisField(),
            $this->getPercentageField(),
        ];
    }

    public function getPercentageField(): array
    {
        return [
            'type'     => 'select',
            'name'     => 'show_percentage',
            'title'    => trans('reports.percentage_of_income'),
            'icon'     => 'percent',
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

    public function showPercentage(): bool
    {
        return $this->getSearchStringValue('show_percentage', $this->getSetting('show_percentage')) === 'yes';
    }

    public function getPercentageOfIncome(string $date, float|int $cell_value): ?string
    {
        if (! $this->showPercentage()) {
            return null;
        }

        $income_total = $this->footer_totals['income'][$date] ?? 0;

        if ($income_total == 0) {
            return null;
        }

        $pct = round($cell_value / $income_total * 100, 1);

        return setting('localisation.percent_position') == 'after'
            ? $pct . '%'
            : '%' . $pct;
    }

    public function getDrillDownUrl(string $date, int|string $id): string
    {
        [$date_start, $date_end] = $this->getDateRangeForDrillDown($date);

        $group = $this->getGroup();

        // category_id:519 paid_at>=2026-03-01 paid_at<=2026-03-29
        $search = implode(
            separator: ' ',
            array: [
                "{$group}_id:{$id}",
                "paid_at>={$date_start}",
                "paid_at<={$date_end}",
            ],
        );

        return route('transactions.index') . '?list_records=all&search=' . $search;
    }

    private function getDateRangeForDrillDown(string $date): array
    {
        switch ($this->getPeriod()) {
            case 'yearly':
                $range = [
                    trim($date) . '-01-01',
                    trim($date) . '-12-31',
                ];

                break;
            case 'quarterly':
                [$d_start, $d_end] = array_map('trim', explode(' - ', $date, 2));

                $range = [
                    Date::createFromFormat('M Y', $d_start)->startOfMonth()->format('Y-m-d'),
                    Date::createFromFormat('M Y', $d_end)->endOfMonth()->format('Y-m-d'),
                ];

                break;
            case 'weekly':
                [$d_start, $d_end] = array_map('trim', explode(' - ', $date, 2));

                $range = [
                    Date::createFromFormat('d M Y', $d_start)->startOfDay()->format('Y-m-d'),
                    Date::createFromFormat('d M Y', $d_end)->endOfDay()->format('Y-m-d'),
                ];

                break;
            default: // monthly
                $range = [
                    Date::createFromFormat('M Y', $date)->startOfMonth()->format('Y-m-d'),
                    Date::createFromFormat('M Y', $date)->endOfMonth()->format('Y-m-d'),
                ];

                break;
        }

        // Clamp to report's start_date/end_date if present
        $report_start = request('start_date');
        $report_end = request('end_date');

        if ($report_start && $report_end) {
            $range[0] = max($range[0], $report_start);
            $range[1] = min($range[1], $report_end);
        }

        return $range;
    }
}

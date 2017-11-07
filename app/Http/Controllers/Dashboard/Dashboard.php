<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Banking\Account;
use App\Models\Expense\Bill;
use App\Models\Expense\BillPayment;
use App\Models\Expense\Payment;
use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\Revenue;
use App\Models\Setting\Category;
use App\Traits\Currencies;
use Date;

class Dashboard extends Controller
{
    use Currencies;

    public $today;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $this->today = Date::today();
        $month_days = $this->today->daysInMonth;

        /*
         * Cash Flow
         */

        // Daily
        $day = array();

        for ($j = $month_days; $j > -1; $j--) {
            $day[$month_days - $j] = date("d M", strtotime("-$j day"));
        }

        $daily_income = $this->getCashFlow('income', 'daily');
        $daily_expense = $this->getCashFlow('expense', 'daily');

        $daily_profit = $this->getProfit($daily_income, $daily_expense);

        // Monthly
        $month = array();

        for ($j = 12; $j >= 0; $j--) {
            $month[12 - $j] = date("F-Y", strtotime(" -$j month"));
        }

        $monthly_income = $this->getCashFlow('income', 'monthly');
        $monthly_expense = $this->getCashFlow('expense', 'monthly');

        $monthly_profit = $this->getProfit($monthly_income, $monthly_expense);

        $cash_flow = [
            'daily' => [
                'date'    => json_encode($day),
                'income'  => json_encode(array_values($daily_income)),
                'expense' => json_encode(array_values($daily_expense)),
                'profit'  => json_encode(array_values($daily_profit))
            ],
            'monthly' => [
                'date'    => json_encode($month),
                'income'  => json_encode(array_values($monthly_income)),
                'expense' => json_encode(array_values($monthly_expense)),
                'profit'  => json_encode(array_values($monthly_profit))
            ],
        ];

        /*
         * Totals & Pie Charts
         */

        $incomes = $expenses = array();

        $incomes_amount = $expenses_amount = 0;

        // Invoices
        $invoices = Invoice::with('payments')->accrued()->get();
        list($invoice_paid_amount, $open_invoice, $overdue_invoice) = $this->getTotals($invoices, 'invoice');

        $incomes_amount += $invoice_paid_amount;

        // Bills
        $bills = Bill::with('payments')->accrued()->get();
        list($bill_paid_amount, $open_bill, $overdue_bill) = $this->getTotals($bills, 'bill');

        $expenses_amount += $bill_paid_amount;

        // Add to Incomes By Category
        $incomes[] = array(
            'amount'    => money($invoice_paid_amount, setting('general.default_currency'), true)->format(),
            'value'     => (int) $invoice_paid_amount,
            'color'     => '#00c0ef',
            'highlight' => '#00c0ef',
            'label'     => trans_choice('general.invoices', 2)
        );

        // Add to Expenses By Category
        $expenses[] = array(
            'amount'    => money($bill_paid_amount, setting('general.default_currency'), true)->format(),
            'value'     => (int) $bill_paid_amount,
            'color'     => '#dd4b39',
            'highlight' => '#dd4b39',
            'label'     => trans_choice('general.bills', 2)
        );

        // Revenues & Payments
        $categories = Category::orWhere('type', 'income')->orWhere('type', 'expense')->enabled()->get();

        foreach ($categories as $category) {
            switch ($category->type) {
                case 'income':
                    $revenues = $category->revenues;

                    $amount = 0;

                    if ($revenues) {
                        foreach ($revenues as $revenue) {
                            $amount += $revenue->getConvertedAmount();
                        }

                        $incomes[] = array(
                            'amount'    => money($amount, setting('general.default_currency'), true)->format(),
                            'value'     => (int) $amount,
                            'color'     => $category->color,
                            'highlight' => $category->color,
                            'label'     => $category->name
                        );
                    } else {
                        $incomes[] = array(
                            'amount'    => money(0, setting('general.default_currency'), true)->format(),
                            'value'     => (int) 0,
                            'color'     => $category->color,
                            'highlight' => $category->color,
                            'label'     => $category->name
                        );
                    }

                    $incomes_amount += $amount;
                    break;
                case 'expense':
                    $payments = $category->payments;

                    $amount = 0;

                    if ($payments) {
                        foreach ($payments as $payment) {
                            $amount += $payment->getConvertedAmount();
                        }

                        $expenses[] = array(
                            'amount'    => money($amount, setting('general.default_currency'), true)->format(),
                            'value'     => (int) $amount,
                            'color'     => $category->color,
                            'highlight' => $category->color,
                            'label'     => $category->name
                        );
                    } else {
                        $expenses[] = array(
                            'amount'    => money(0, setting('general.default_currency'), true)->format(),
                            'value'     => (int) 0,
                            'color'     => $category->color,
                            'highlight' => $category->color,
                            'label'     => $category->name
                        );
                    }

                    $expenses_amount += $amount;
                    break;
            }
        }

        if (empty($incomes_amount)) {
            foreach ($incomes as $key => $income) {
                $incomes[$key]['amount'] = money(0, setting('general.default_currency'), true)->format();
                $incomes[$key]['value'] = (int) 100 / count($incomes);
            }
        }

        // Incomes Pie Chart
        $income_graph = json_encode($incomes);

        if (empty($expenses_amount)) {
            foreach ($expenses as $key => $expense) {
                $expenses[$key]['amount'] = money(0, setting('general.default_currency'), true)->format();
                $expenses[$key]['value'] = (int) 100 / count($expenses);
            }
        }

        // Expenses Pie Chart
        $expense_graph = json_encode($expenses);

        $incomes_progress = 100;

        if (!empty($open_invoice) && !empty($overdue_invoice)) {
            $incomes_progress = (int) 100 - (100 * ($open_invoice / $overdue_invoice));
        }

        // Totals
        $total_incomes = array(
            'total'             => $incomes_amount,
            'open_invoice'      => money($open_invoice, setting('general.default_currency'), true),
            'overdue_invoice'   => money($overdue_invoice, setting('general.default_currency'), true),
            'progress'          => $incomes_progress
        );

        $expenses_progress = 100;

        if (!empty($open_bill) && !empty($overdue_bill)) {
            $expenses_progress = (int) 100 - (100 * ($open_bill / $overdue_bill));
        }

        $total_expenses = array(
            'total'         => $expenses_amount,
            'open_bill'     => money($open_bill, setting('general.default_currency'), true),
            'overdue_bill'  => money($overdue_bill, setting('general.default_currency'), true),
            'progress'      => $expenses_progress
        );

        $amount_profit = $incomes_amount - $expenses_amount;
        $open_profit = $open_invoice - $open_bill;
        $overdue_profit = $overdue_invoice - $overdue_bill;

        $total_progress = 100;

        if (!empty($open_profit) && !empty($overdue_profit)) {
            $total_progress = (int) 100 - (100 * ($open_profit / $overdue_profit));
        }

        $total_profit = array(
            'total'         => $amount_profit,
            'open'          => money($open_profit, setting('general.default_currency'), true),
            'overdue'       => money($overdue_profit, setting('general.default_currency'), true),
            'progress'      => $total_progress
        );

        /*
         * Accounts
         */

        $accounts = Account::enabled()->get();

        /*
         * Latest Incomes
         */

        $latest_incomes = collect(Invoice::accrued()->latest()->take(10)->get());
        $latest_incomes = $latest_incomes->merge(Revenue::latest()->take(10)->get())->take(5)->sortByDesc('invoiced_at');

        /*
         * Latest Expenses
         */

        $latest_expenses = collect(Bill::accrued()->latest()->take(10)->get());
        $latest_expenses = $latest_expenses->merge(Payment::latest()->take(10)->get())->take(5)->sortByDesc('billed_at');

        return view('dashboard.dashboard.index', compact(
            'total_incomes',
            'total_expenses',
            'total_profit',
            'cash_flow',
            'incomes',
            'incomes_amount',
            'income_graph',
            'expenses',
            'expenses_amount',
            'expense_graph',
            'accounts',
            'latest_incomes',
            'latest_expenses'
        ));
    }

    private function getCashFlow($type, $period)
    {
        $totals = array();

        if ($type == 'income') {
            $m1 = '\App\Models\Income\Revenue';
            $m2 = '\App\Models\Income\InvoicePayment';
        } else {
            $m1 = '\App\Models\Expense\Payment';
            $m2 = '\App\Models\Expense\BillPayment';
        }

        switch ($period) {
            case 'yearly':
                $f1 = 'subYear';
                $f2 = 'addYear';

                $date_format = 'Y';
                break;
            case 'monthly':
                $f1 = 'subYear';
                $f2 = 'addMonth';

                $date_format = 'Y-m';
                break;
            default:
            case 'daily':
                $f1 = 'subMonth';
                $f2 = 'addDay';

                $date_format = 'Y-m-d';
                break;
        }

        $now = Date::now();
        $sub = Date::now()->$f1();

        $start_date = $sub->format($date_format);
        $end_date = $now->format($date_format);
        $next_date = $start_date;

        $totals[$start_date] = 0;

        do {
            $next_date = Date::parse($next_date)->$f2()->format($date_format);

            $totals[$next_date] = 0;
        } while ($next_date < $end_date);

        $items_1 = $m1::whereBetween('paid_at', [$sub, $now])->get();

        $this->setCashFlowTotals($totals, $items_1, $date_format);

        $items_2 = $m2::whereBetween('paid_at', [$sub, $now])->get();

        $this->setCashFlowTotals($totals, $items_2, $date_format);

        return $totals;
    }

    private function setCashFlowTotals(&$totals, $items, $date_format)
    {
        foreach ($items as $item) {
            $i = Date::parse($item->paid_at)->format($date_format);

            $totals[$i] += $item->getConvertedAmount();
        }
    }

    private function getTotals($items, $type)
    {
        $paid = $open = $overdue = 0;

        $today = $this->today->toDateString();

        foreach ($items as $item) {
            $paid += $item->getConvertedAmount();

            $code_field = $type . '_status_code';

            if ($item->$code_field == 'paid') {
                continue;
            }

            $payments = 0;
            if ($item->$code_field == 'partial') {
                foreach ($item->payments as $payment) {
                    $payments += $payment->getConvertedAmount();
                }
            }

            // Check if it's open or overdue invoice
            if ($item->due_at > $today) {
                $open += $item->getConvertedAmount() - $payments;
            } else {
                $overdue += $item->getConvertedAmount() - $payments;
            }
        }

        return array($paid, $open, $overdue);
    }

    private function getProfit($incomes, $expenses)
    {
        $profit = [];

        foreach ($incomes as $key => $income) {
            if ($income > 0 && $income > $expenses[$key]) {
                $profit[$key] = $income - $expenses[$key];
            } else {
                $profit[$key] = 0;
            }
        }

        return $profit;
    }
}

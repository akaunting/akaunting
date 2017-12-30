<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Income\Invoice;
use Charts;
use Date;

class Dashboard extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $customer = auth()->user()->customer;

        $invoices = Invoice::with('status')->accrued()->where('customer_id', $customer->id)->get();

        $start = Date::parse(request('start', Date::today()->startOfYear()->format('Y-m-d')));
        $end = Date::parse(request('end', Date::today()->endOfYear()->format('Y-m-d')));

        $start_month = $start->month;
        $end_month = $end->month;

        // Monthly
        $labels = [];

        $s = clone $start;

        for ($j = $end_month; $j >= $start_month; $j--) {
            $labels[$end_month - $j] = $s->format('M Y');

            $s->addMonth();
        }

        $unpaid = $paid = $overdue = $partial_paid = [];

        foreach ($invoices as $invoice) {
            switch ($invoice->invoice_status_code) {
                case 'paid':
                    $paid[] = $invoice;
                    break;
                case 'partial':
                    $partial_paid[] = $invoice;
                    break;
                case 'sent':
                default:
                    if (Date::today()->format('Y-m-d') > $invoice->due_at->format('Y-m-d')) {
                        $overdue[] = $invoice;
                    } else {
                        $unpaid[] = $invoice;
                    }
            }
        }

        $total = count($unpaid) + count($paid) + count($partial_paid) + count($overdue);

        $progress = [
            'unpaid' => count($unpaid),
            'paid' => count($paid),
            'overdue' => count($overdue),
            'partially_paid' => count($partial_paid),
            'total' => $total,
        ];

        $unpaid = $this->calculateTotals($unpaid, $start, $end, 'unpaid');
        $paid = $this->calculateTotals($paid, $start, $end, 'paid');
        $partial_paid = $this->calculateTotals($partial_paid, $start, $end, 'partial');
        $overdue = $this->calculateTotals($overdue, $start, $end, 'overdue');

        $chart = Charts::multi('line', 'chartjs')
            ->dimensions(0, 300)
            ->colors(['#dd4b39', '#6da252', '#f39c12', '#00c0ef'])
            ->dataset(trans('general.unpaid'), $unpaid)
            ->dataset(trans('general.paid'), $paid)
            ->dataset(trans('general.overdue'), $overdue)
            ->dataset(trans('general.partially_paid'), $partial_paid)
            ->labels($labels)
            ->credits(false)
            ->view('vendor.consoletvs.charts.chartjs.multi.line');

        return view('customers.dashboard.index', compact('customer', 'invoices', 'progress', 'chart'));
    }

    private function calculateTotals($items, $start, $end, $type)
    {
        $totals = [];

        $date_format = 'Y-m';

        $n = 1;
        $start_date = $start->format($date_format);
        $end_date = $end->format($date_format);
        $next_date = $start_date;

        $s = clone $start;

        while ($next_date <= $end_date) {
            $totals[$next_date] = 0;

            $next_date = $s->addMonths($n)->format($date_format);
        }

        $this->setTotals($totals, $items, $date_format, $type);

        return $totals;
    }

    private function setTotals(&$totals, $items, $date_format, $type)
    {
        foreach ($items as $item) {
            if ($type == 'partial') {
                $item->amount = $item->payments()->paid();
            }

            $i = Date::parse($item->paid_at)->format($date_format);

            $totals[$i] += $item->getConvertedAmount();
        }
    }
}

<?php

namespace App\Http\Controllers\Portal;

use App\Models\Sale\Invoice;
use App\Traits\Charts;
use App\Utilities\Chartjs;
use Date;

class Dashboard
{
    use Charts;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $contact = user()->contact;

        $invoices = Invoice::accrued()->where('contact_id', $contact->id)->get();

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
            switch ($invoice->status) {
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

        $chart = new Chartjs();
        $chart->type('line')
            ->width(0)
            ->height(300)
            ->options($this->getLineChartOptions())
            ->labels(array_values($labels));

        $chart->dataset(trans('general.unpaid'), 'line', array_values($unpaid))
        ->backgroundColor('#ef3232')
        ->color('#ef3232')
        ->options([
            'borderWidth' => 4,
            'pointStyle' => 'line',
         ])
        ->fill(false);

        $chart->dataset(trans('general.paid'), 'line', array_values($paid))
        ->backgroundColor('#6da252')
        ->color('#6da252')
        ->options([
            'borderWidth' => 4,
            'pointStyle' => 'line',
         ])
        ->fill(false);

        $chart->dataset(trans('general.overdue'), 'line', array_values($overdue))
        ->backgroundColor('#efad32')
        ->color('#efad32')
        ->options([
            'borderWidth' => 4,
            'pointStyle' => 'line',
         ])
        ->fill(false);

        $chart->dataset(trans('general.partially_paid'), 'line', array_values($partial_paid))
        ->backgroundColor('#328aef')
        ->color('#328aef')
        ->options([
            'borderWidth' => 4,
            'pointStyle' => 'line',
         ])
        ->fill(false);


        return view('portal.dashboard.index', compact('contact', 'invoices', 'progress', 'chart'));
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
                $item->amount = $item->transactions()->paid();
            }

            $i = Date::parse($item->paid_at)->format($date_format);

            $totals[$i] += $item->getAmountConvertedToDefault();
        }
    }
}

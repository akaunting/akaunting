<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Models\Income\Invoice;
use App\Utilities\Chartjs;
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
        $contact = user()->contact;

        $invoices = Invoice::with('status')->accrued()->where('contact_id', $contact->id)->get();

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

        $options = [
            'tooltips' => [
                'backgroundColor' => '#f5f5f5',
                'titleFontColor' => '#333',
                'bodyFontColor' => '#666',
                'bodySpacing' => 4,
                'YrPadding' => 12,
                'mode' => 'nearest',
                'intersect' => 0,
                'position' => 'nearest'
            ],
              'responsive' => true,
              'scales' => [

                'yAxes' => [
                    [
                        'barPercentage' => 1.6,
                        'gridLines' => [
                        'drawBorder' => false,
                        'color' => 'rgba(29,140,248,0.1)',
                        'zeroLineColor' => 'transparent',
                        'borderDash' => [2],
                        'borderDashOffset' => [2],
                        ],
                        'ticks' => [
                        'padding' => 10,
                        'fontColor' => '#9e9e9e'
                        ]
                    ]
                ],

                'xAxes' => [
                    [
                        'barPercentage' => 1.6,
                        'gridLines' => [
                          'drawBorder' => false,
                          'color' => 'rgba(29,140,248,0.0)',
                          'zeroLineColor' => 'transparent'
                        ],
                        'ticks' => [
                          'suggestedMin' => 60,
                          'suggestedMax' => 125,
                          'padding' => 20,
                          'fontColor' => '#9e9e9e'
                        ]
                    ]
                ]

            ]
        ];

        $chart = new Chartjs();
        $chart->type('line')
            ->width(0)
            ->height(300)
            ->options($options)
            ->labels($labels);

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
                $item->amount = $item->payments()->paid();
            }

            $i = Date::parse($item->paid_at)->format($date_format);

            $totals[$i] += $item->getAmountConvertedToDefault();
        }
    }
}

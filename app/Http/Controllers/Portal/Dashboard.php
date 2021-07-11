<?php

namespace App\Http\Controllers\Portal;

use App\Models\Document\Document;
use App\Traits\Charts;
use App\Traits\DateTime;
use App\Utilities\Chartjs;
use Date;

class Dashboard
{
    use Charts, DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $contact = user()->contact;

        // Redirect user redirect landing page..
        if (!$contact) {
            return redirect(user()->getLandingPageOfUser());
        }

        $financial_start = $this->getFinancialStart()->format('Y-m-d');

        // check and assign year start
        if (($year_start = Date::today()->startOfYear()->format('Y-m-d')) !== $financial_start) {
            $year_start = $financial_start;
        }

        $start = Date::parse(request('start_date', $year_start));
        $end = Date::parse(request('end_date', Date::parse($year_start)->addYear(1)->subDays(1)->format('Y-m-d')));

        //$invoices = Document::invoice()->accrued()->where('contact_id', $contact->id)->get();
        $invoices = Document::invoice()->accrued()->whereBetween('due_at', [$start, $end])->where('contact_id', $contact->id)->get();

        $start_month = $start->month;
        $end_month = $end->month;

        // look cashFlow widget
        $end_month   = $end->diffInMonths($start);
        $start_month = 0;
        // Monthly
        $labels = [];

        $s = clone $start;

        for ($j = $end_month; $j >= $start_month; $j--) {
            $labels[$end_month - $j] = $s->format('M Y');

            $s->addMonth();
        }

        $amounts = $this->calculateAmounts($invoices, $start, $end);

        $grand = array_sum($amounts['unpaid']) + array_sum($amounts['paid']) + array_sum($amounts['overdue']);

        $totals = [
            'paid' => money(array_sum($amounts['paid']), setting('default.currency'), true),
            'unpaid' => money(array_sum($amounts['unpaid']), setting('default.currency'), true),
            'overdue' => money(array_sum($amounts['overdue']), setting('default.currency'), true),
        ];

        $progress = [
            'paid' => !empty($grand) ? (100 / $grand) * array_sum($amounts['paid']) : '0',
            'unpaid' => !empty($grand) ? (100 / $grand) * array_sum($amounts['unpaid']) : '0',
            'overdue' => !empty($grand) ? (100 / $grand) * array_sum($amounts['overdue']) : '0',
        ];

        $chart = new Chartjs();
        $chart->type('line')
            ->width(0)
            ->height(300)
            ->options($this->getLineChartOptions())
            ->labels(array_values($labels));

        $chart->dataset(trans('general.paid'), 'line', array_values($amounts['paid']))
            ->backgroundColor('#6da252')
            ->color('#6da252')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
            ])
            ->fill(false);

        $chart->dataset(trans('general.unpaid'), 'line', array_values($amounts['unpaid']))
            ->backgroundColor('#efad32')
            ->color('#efad32')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
            ])
            ->fill(false);

        $chart->dataset(trans('general.overdue'), 'line', array_values($amounts['overdue']))
            ->backgroundColor('#ef3232')
            ->color('#ef3232')
            ->options([
                'borderWidth' => 4,
                'pointStyle' => 'line',
            ])
            ->fill(false);

        $date_picker_shortcuts = $this->getDatePickerShortcuts();

        if (!request()->has('start_date')) {
            request()->merge(['start_date' => $date_picker_shortcuts[trans('reports.this_year')]['start']]);
            request()->merge(['end_date' => $date_picker_shortcuts[trans('reports.this_year')]['end']]);
        }

        return view('portal.dashboard.index', compact('contact', 'invoices', 'totals', 'progress', 'chart', 'date_picker_shortcuts'));
    }

    private function calculateAmounts($invoices, $start, $end)
    {
        $amounts = ['paid', 'unpaid', 'overdue'];

        $date_format = 'Y-m';

        $n = 1;
        $start_date = $start->format($date_format);
        $end_date = $end->format($date_format);
        $next_date = $start_date;

        $s = clone $start;

        while ($next_date <= $end_date) {
            $amounts['paid'][$next_date] = $amounts['unpaid'][$next_date] = $amounts['overdue'][$next_date] = 0;

            $next_date = $s->addMonths($n)->format($date_format);
        }

        $this->setAmounts($amounts, $invoices, $date_format);

        return $amounts;
    }

    private function setAmounts(&$amounts, $invoices, $date_format)
    {
        $today = Date::today()->format('Y-m-d');

        foreach ($invoices as $invoice) {
            $date = Date::parse($invoice->due_at)->format($date_format);

            $amount = $invoice->getAmountConvertedToDefault();

            $is_overdue = $today > $invoice->due_at->format('Y-m-d');

            switch ($invoice->status) {
                case 'paid':
                    $amounts['paid'][$date] += $amount;
                    break;
                case 'partial':
                    $paid = $invoice->paid;
                    $remainder = $amount - $paid;

                    $amounts['paid'][$date] += $paid;

                    if ($is_overdue) {
                        $amounts['overdue'][$date] += $remainder;
                    } else {
                        $amounts['unpaid'][$date] += $remainder;
                    }
                    break;
                default:
                    if ($is_overdue) {
                        $amounts['overdue'][$date] += $amount;
                    } else {
                        $amounts['unpaid'][$date] += $amount;
                    }
            }
        }
    }
}

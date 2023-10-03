<?php

namespace App\Http\Controllers\Portal;

use App\Models\Document\Document;
use App\Traits\Charts;
use App\Traits\DateTime;
use App\Utilities\Date;

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

        $financial_year = $this->getFinancialYear();

        $start = Date::parse(request('start_date', $financial_year->copy()->getStartDate()->toDateString()));
        $end = Date::parse(request('end_date', $financial_year->copy()->getEndDate()->toDateString()));

        //$invoices = Document::invoice()->accrued()->where('contact_id', $contact->id)->get();
        $invoices = Document::invoice()->accrued()->whereBetween('due_at', [$start, $end])->where('contact_id', $contact->id)->get();

        $amounts = $this->calculateAmounts($invoices, $start, $end);

        return view('portal.dashboard.index', compact('contact', 'invoices'));
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

<?php

namespace App\Abstracts;

use App\Events\Document\PaidAmountCalculated;
use App\Models\Common\Report;
use App\Models\Document\Document;
use App\Traits\Charts;
use App\Traits\DateTime;
use App\Utilities\Date;
use App\Utilities\Reports;
use Illuminate\Database\Eloquent\Builder;

abstract class Widget
{
    use Charts, DateTime;

    public $model;

    public $default_name = '';

    public $default_settings = [
        'width' => '50',
    ];

    public $description = '';

    public $report_class = '';

    public $views = [
        'header' => 'components.widgets.header',
    ];

    /**
     * Methods that may be invoked via the Widgets@getData endpoint.
     * Subclasses can append additional safe, read-only methods.
     *
     * @var array
     */
    public array $allowed_methods = ['show'];

    public array $data = [];

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function getDefaultName()
    {
        return trans($this->default_name);
    }

    public function getDefaultSettings()
    {
        return $this->default_settings;
    }

    public function getDescription()
    {
        return trans($this->description);
    }

    public function getReportUrl(): string
    {
        $empty_url = '';

        if (empty($this->report_class)) {
            return $empty_url;
        }

        if (Reports::isModule($this->report_class) && Reports::isModuleDisabled($this->report_class)) {
            $alias = Reports::getModuleAlias($this->report_class);

            return route('apps.app.show', [
                'alias'         => $alias,
                'utm_source'    => 'widget',
                'utm_medium'    => 'app',
                'utm_campaign'  => str_replace('-', '_', $alias),
            ]);
        }

        if (! class_exists($this->report_class)) {
            return $empty_url;
        }

        if (Reports::cannotRead($this->report_class)) {
            return $empty_url;
        }

        $model = Report::where('class', $this->report_class)->first();

        if (! $model instanceof Report) {
            return route('reports.create');
        }

        return route('reports.show', $model->id);
    }

    public function getViews()
    {
        return $this->views;
    }

    public function view($name, $data = [])
    {
        if (request_is_api()) {
            return $data;
        }

        return view($name, array_merge(['class' => $this], (array) $data));
    }

    public function applyFilters($query, $args = ['date_field' => 'paid_at'])
    {
        return $this->scopeDateFilter($query, $args['date_field']);
    }

    /*
    * @deprecated 3.2.5 Use the new calculateOpenAndOverdueTotals() method instead.
    */
    public function calculateDocumentTotals($model)
    {
        $open = $overdue = 0;

        $today = Date::today()->toDateString();

        if ($model->status == 'paid') {
            return [$open, $overdue];
        }

        $payments = 0;

        if ($model->status == 'partial') {
            foreach ($model->transactions as $transaction) {
                $payments += $transaction->getAmountConvertedToDefault();
            }
        }

        // Check if the invoice/bill is open or overdue
        if ($model->due_at > $today) {
            $open += $model->getAmountConvertedToDefault() - $payments;
        } else {
            $overdue += $model->getAmountConvertedToDefault() - $payments;
        }

        return [$open, $overdue];
    }

    /**
     * The data of the Receivables and Payables widgets: the documents of the query overdue at the end
     * of today (calculateOpenAndOverdueTotals()), formatted for their view.
     */
    protected function setOpenAndOverdueData(Builder $query, string $grand_total_text): void
    {
        [$open, $overdue, $periods] = $this->calculateOpenAndOverdueTotals($query);

        foreach ($periods as $period_name => $period_amount) {
            $periods[$period_name] = money($period_amount);
        }

        $has_progress = !empty($open) || !empty($overdue);
        $progress = !empty($open) ? (int) ($open * 100) / ($open + $overdue) : 0;

        $grand = $open + $overdue;

        $totals = [
            'grand'     => money($grand),
            'open'      => money($open),
            'overdue'   => money($overdue),
        ];

        $this->data = [
            'totals'            => $totals,
            'has_progress'      => $has_progress,
            'progress'          => $progress,
            'periods'           => $periods,
            'grand_total_text'  => $grand_total_text,
        ];
    }

    /**
     * What is still to be received or paid on the documents of the query at the end of today,
     * whatever the dashboard's date range: every unpaid document, future-dated ones included,
     * less the payments dated by then. Not yet due, or due today, is open. The rest is overdue,
     * split by whole days past due into 1-30, 31-60, 61-90 and over 90, as in the Aged
     * Receivables/Payables report, which leaves future-dated documents out of its as-of date.
     *
     * @return array{0: float, 1: float, 2: array<string, float>} open, overdue and the overdue periods
     */
    protected function calculateOpenAndOverdueTotals(Builder $query): array
    {
        $open = $overdue = 0;

        $periods = [
            'overdue_1_30' => 0,
            'overdue_31_60' => 0,
            'overdue_61_90' => 0,
            'overdue_91_un' => 0,
        ];

        $as_of = Date::today()->endOfDay();

        $query->accrued()
            // A paid document still counts when one of its payments is dated after the as-of moment
            ->where(fn ($q) => $q
                ->notPaid()
                ->orWhereHas('transactions', fn ($q) => $q->where('paid_at', '>', $as_of))
            )
            ->with('transactions')
            ->each(function ($document) use ($as_of, &$open, &$overdue, &$periods) {
                $balance = $this->getDocumentBalance($document, $as_of);

                if ($balance == 0) {
                    return;
                }

                $age = $this->getDaysPastDue($document, $as_of);

                if ($age <= 0) {
                    $open += $balance;

                    return;
                }

                $overdue += $balance;

                $period = match (true) {
                    $age <= 30 => 'overdue_1_30',
                    $age <= 60 => 'overdue_31_60',
                    $age <= 90 => 'overdue_61_90',
                    default => 'overdue_91_un',
                };

                $periods[$period] += $balance;
            });

        return [$open, $overdue, $periods];
    }

    /**
     * What is still owed at the as-of moment, in the default currency. It is worked out in the
     * document's currency, as the paid amount is, so that a payment at another rate leaves no
     * exchange difference. A paid document whose payments all count is settled, which absorbs the
     * cent a conversion can leave. Apps add what settles a document outside banking transactions,
     * such as credits applied through Credit Debit Notes, through PaidAmountCalculated, as they do
     * for core's payment jobs.
     */
    protected function getDocumentBalance(Document $document, Date $as_of): float
    {
        $paid = 0;
        $all_counted = true;

        foreach ($document->transactions as $transaction) {
            if ($transaction->paid_at->greaterThan($as_of)) {
                $all_counted = false;

                continue;
            }

            $amount = $transaction->amount;

            if ($transaction->currency_code != $document->currency_code) {
                $amount = $document->convertBetween($amount, $transaction->currency_code, $transaction->currency_rate, $document->currency_code, $document->currency_rate);
            }

            $paid += $amount;
        }

        if ($all_counted && ($document->status == 'paid')) {
            return 0;
        }

        $document->paid_amount = $paid;
        event(new PaidAmountCalculated($document));
        $paid = (float) $document->paid_amount;
        unset($document->paid_amount);

        $balance = round($document->amount - $paid, currency($document->currency_code)->getPrecision());

        if ($balance == 0) {
            return 0;
        }

        return $document->convertToDefault($balance, $document->currency_code, $document->currency_rate);
    }

    /**
     * Whole days from the due date to the as-of date, both the company's calendar dates, so the
     * time of day on the due date plays no part. They are counted on UTC midnights, which are
     * always 24 hours apart, unlike a day when daylight saving starts at midnight.
     */
    protected function getDaysPastDue(Document $document, Date $as_of): int
    {
        // rawParse(): a Y-m-d string needs none of parse()'s locale handling, and this runs per document
        return (int) Date::rawParse($document->due_at->toDateString(), 'UTC')
            ->diffInDays(Date::rawParse($as_of->toDateString(), 'UTC'), false);
    }
}

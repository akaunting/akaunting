<?php

namespace App\Abstracts;

use App\Models\Common\Report;
use App\Traits\Charts;
use App\Utilities\Date;
use App\Utilities\Reports;
use Illuminate\Support\Str;

abstract class Widget
{
    use Charts;

    public $model;

    public $default_name = '';

    public $default_settings = [
        'width' => 'w-full lg:w-2/4 lg:px-12 my-8',
    ];

    public $description = '';

    public $report_class = '';

    public $views = [
        'header' => 'components.widgets.header',
    ];

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
        if (request()->isApi()) {
            return $data;
        }

        return view($name, array_merge(['class' => $this], (array) $data));
    }

    public function applyFilters($model, $args = ['date_field' => 'paid_at'])
    {
        if (empty(request()->get('start_date', null))) {
            return $model;
        }

        $start_date = request()->get('start_date') . ' 00:00:00';
        $end_date = request()->get('end_date') . ' 23:59:59';

        return $model->whereBetween($args['date_field'], [$start_date, $end_date]);
    }

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
}

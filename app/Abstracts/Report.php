<?php

namespace App\Abstracts;

use App\Events\Report\DataLoaded;
use App\Events\Report\DataLoading;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;
use App\Exports\Common\Reports as Export;
use App\Models\Common\Report as Model;
use App\Models\Document\Document;
use App\Traits\Charts;
use App\Traits\DateTime;
use App\Traits\SearchString;
use App\Utilities\Chartjs;
use App\Utilities\Date;
use App\Utilities\Export as ExportHelper;
use Illuminate\Support\Str;

abstract class Report
{
    use Charts, DateTime, SearchString;

    public $model;

    public $default_name = '';

    public $category = 'reports.income_expense';

    public $icon = 'fa fa-chart-pie';

    public $has_money = true;

    public $year;

    public $views = [];

    public $tables = [];

    public $dates = [];

    public $row_names = [];

    public $row_values = [];

    public $footer_totals = [];

    public $filters = [];

    public $loaded = false;

    public $chart = [
        'line' => [
            'width' => '0',
            'height' => '300',
            'options' => [
                'color' => '#6da252',
                'legend' => [
                    'display' => false,
                ],
            ],
        ],
        'dates' => [],
        'datasets' => [],
    ];

    public $column_name_width = 'report-column-name';
    public $column_value_width = 'report-column-value';

    public function __construct(Model $model = null, $load_data = true)
    {
        $this->setGroups();

        if (!$model) {
            return;
        }

        $this->model = $model;

        if (!$load_data) {
            return;
        }

        $this->load();
    }

    abstract public function setData();

    public function load()
    {
        $this->setYear();
        $this->setViews();
        $this->setTables();
        $this->setDates();
        $this->setFilters();
        $this->setRows();
        $this->loadData();
        $this->setColumnWidth();

        $this->loaded = true;
    }

    public function loadData()
    {
        event(new DataLoading($this));

        $this->setData();

        event(new DataLoaded($this));
    }

    public function getDefaultName()
    {
        if (!empty($this->default_name)) {
            return trans($this->default_name);
        }

        return Str::title(str_replace('_', ' ', Str::snake((new \ReflectionClass($this))->getShortName())));
    }

    public function getCategory()
    {
        return trans($this->category);
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getGrandTotal()
    {
        if (!$this->loaded) {
            $this->load();
        }

        if (!empty($this->footer_totals)) {
            $sum = 0;

            foreach ($this->footer_totals as $total) {
                $sum += is_array($total) ? array_sum($total) : $total;
            }

            $total = $this->has_money ? money($sum, setting('default.currency'), true)->format() : $sum;
        } else {
            $total = trans('general.na');
        }

        return $total;
    }

    public function getChart()
    {
        $chart = new Chartjs();

        if (!$type = $this->getSetting('chart')) {
            return $chart;
        }

        $config = $this->chart[$type];

        $default_options = $this->getLineChartOptions();

        $options = array_merge($default_options, (array) $config['options']);

        $chart->type($type)
            ->width((int) $config['width'])
            ->height((int) $config['height'])
            ->options($options)
            ->labels(!empty($config['dates']) ? array_values($config['dates']) : array_values($this->dates));

        if (!empty($config['datasets'])) {
            foreach ($config['datasets'] as $dataset) {
                $chart->dataset($dataset['name'], 'line', array_values($dataset['totals']))
                    ->backgroundColor(isset($dataset['backgroundColor']) ? $dataset['backgroundColor'] : '#6da252')
                    ->color(isset($dataset['color']) ? $dataset['color'] : '#6da252')
                    ->options((array) $dataset['options'])
                    ->fill(false);
            }
        } else {
            foreach ($this->footer_totals as $total) {
                $chart->dataset($this->model->name, 'line', array_values($total))
                    ->backgroundColor(isset($config['backgroundColor']) ? $config['backgroundColor'] : '#6da252')
                    ->color(isset($config['color']) ? $config['color'] : '#6da252')
                    ->options([
                        'borderWidth' => 4,
                        'pointStyle' => 'line',
                    ])
                    ->fill(false);
            }
        }

        return $chart;
    }

    public function show()
    {
        return view($this->views['show'])->with('class', $this);
    }

    public function print()
    {
        return view($this->views['print'])->with('class', $this);
    }

    public function export()
    {
        return ExportHelper::toExcel(new Export($this->views['content'], $this), $this->model->name);
    }

    public function setColumnWidth()
    {
        if (!$period = $this->getSetting('period')) {
            return;
        }

        $width = '';

        switch ($period) {
            case 'quarterly':
                $width = 'col-sm-2';
                break;
            case 'yearly':
                $width = 'col-sm-4';
                break;
        }

        if (empty($width)) {
            return;
        }

        $this->column_name_width = $this->column_value_width = $width;
    }

    public function setYear()
    {
        $this->year = $this->getSearchStringValue('year', Date::now()->year);
    }

    public function setViews()
    {
        $this->views = [
            'chart' => 'partials.reports.chart',
            'content' => 'partials.reports.content',
            'content.header' => 'partials.reports.content.header',
            'content.footer' => 'partials.reports.content.footer',
            'show' => 'partials.reports.show',
            'header' => 'partials.reports.header',
            'filter' => 'partials.reports.filter',
            'print' => 'partials.reports.print',
            'table' => 'partials.reports.table',
            'table.footer' => 'partials.reports.table.footer',
            'table.header' => 'partials.reports.table.header',
            'table.rows' => 'partials.reports.table.rows',
        ];
    }

    public function setTables()
    {
        $this->tables = [
            'default' => 'default',
        ];
    }

    public function setDates()
    {
        if (!$period = $this->getSetting('period')) {
            return;
        }

        $function = 'sub' . ucfirst(str_replace('ly', '', $period));

        $start = $this->getFinancialStart($this->year)->copy()->$function();

        for ($j = 1; $j <= 12; $j++) {
            switch ($period) {
                case 'yearly':
                    $start->addYear();

                    $j += 11;

                    break;
                case 'quarterly':
                    $start->addQuarter();

                    $j += 2;

                    break;
                default:
                    $start->addMonth();

                    break;
            }

            $date = $this->getFormattedDate($start);

            $this->dates[] = $date;

            foreach ($this->tables as $table) {
                $this->footer_totals[$table][$date] = 0;
            }
        }
    }

    public function setFilters()
    {
        event(new FilterShowing($this));
    }

    public function setGroups()
    {
        $this->groups = [];

        event(new GroupShowing($this));
    }

    public function setRows()
    {
        event(new RowsShowing($this));
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default', $with_tax = true)
    {
        $group_field = $this->getSetting('group') . '_id';

        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $date = $this->getFormattedDate(Date::parse($item->$date_field));

            if (!isset($item->$group_field)) {
                continue;
            }

            $group = $item->$group_field;

            if (
                !isset($this->row_values[$table][$group])
                || !isset($this->row_values[$table][$group][$date])
                || !isset($this->footer_totals[$table][$date])
            ) {
                continue;
            }

            $amount = $item->getAmountConvertedToDefault(false, $with_tax);

            $type = ($item->type === Document::INVOICE_TYPE || $item->type === 'income') ? 'income' : 'expense';

            if (($check_type == false) || ($type == 'income')) {
                $this->row_values[$table][$group][$date] += $amount;

                $this->footer_totals[$table][$date] += $amount;
            } else {
                $this->row_values[$table][$group][$date] -= $amount;

                $this->footer_totals[$table][$date] -= $amount;
            }
        }
    }

    public function setArithmeticTotals($items, $date_field, $operator = 'add', $table = 'default', $amount_field = 'amount')
    {
        $group_field = $this->getSetting('group') . '_id';

        $function = $operator . 'ArithmeticAmount';

        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $date = $this->getFormattedDate(Date::parse($item->$date_field));

            if (!isset($item->$group_field)) {
                continue;
            }

            $group = $item->$group_field;

            if (
                !isset($this->row_values[$table][$group])
                || !isset($this->row_values[$table][$group][$date])
                || !isset($this->footer_totals[$table][$date])
            ) {
                continue;
            }

            $amount = isset($item->$amount_field) ? $item->$amount_field : 1;

            $this->$function($this->row_values[$table][$group][$date], $amount);
            $this->$function($this->footer_totals[$table][$date], $amount);
        }
    }

    public function addArithmeticAmount(&$current, $amount)
    {
        $current = $current + $amount;
    }

    public function subArithmeticAmount(&$current, $amount)
    {
        $current = $current - $amount;
    }

    public function mulArithmeticAmount(&$current, $amount)
    {
        $current = $current * $amount;
    }

    public function divArithmeticAmount(&$current, $amount)
    {
        $current = $current / $amount;
    }

    public function modArithmeticAmount(&$current, $amount)
    {
        $current = $current % $amount;
    }

    public function expArithmeticAmount(&$current, $amount)
    {
        $current = $current ** $amount;
    }

    public function applyFilters($model, $args = [])
    {
        event(new FilterApplying($this, $model, $args));

        return $model;
    }

    public function applyGroups($model, $args = [])
    {
        event(new GroupApplying($this, $model, $args));

        return $model;
    }

    public function getFormattedDate($date)
    {
        $formatted_date = null;

        switch ($this->getSetting('period')) {
            case 'yearly':
                $financial_year = $this->getFinancialYear($this->year);

                if ($date->greaterThanOrEqualTo($financial_year->getStartDate()) && $date->lessThanOrEqualTo($financial_year->getEndDate())) {
                    if (setting('localisation.financial_denote') == 'begins') {
                        $formatted_date = $financial_year->getStartDate()->copy()->format($this->getYearlyDateFormat());
                    } else {
                        $formatted_date = $financial_year->getEndDate()->copy()->format($this->getYearlyDateFormat());
                    }
                }

                break;
            case 'quarterly':
                $quarters = $this->getFinancialQuarters($this->year);

                foreach ($quarters as $quarter) {
                    if ($date->lessThan($quarter->getStartDate()) || $date->greaterThan($quarter->getEndDate())) {
                        continue;
                    }

                    $start = $quarter->getStartDate()->format($this->getQuarterlyDateFormat($this->year));
                    $end = $quarter->getEndDate()->format($this->getQuarterlyDateFormat($this->year));

                    $formatted_date = $start . '-' . $end;
                }

                break;
            default:
                $formatted_date = $date->copy()->format($this->getMonthlyDateFormat($this->year));

                break;
        }

        return $formatted_date;
    }

    public function getUrl($action = 'print')
    {
        $url = company_id() . '/common/reports/' . $this->model->id . '/' . $action;

        $search = request('search');

        if (!empty($search)) {
            $url .= '?search=' . $search;
        }

        return $url;
    }

    public function getSetting($name, $default = '')
    {
        return $this->model->settings->$name ?? $default;
    }

    public function getFields()
    {
        return [
            $this->getGroupField(),
            $this->getPeriodField(),
            $this->getBasisField(),
            $this->getChartField(),
        ];
    }

    public function getGroupField()
    {
        $this->setGroups();

        return [
            'type' => 'selectGroup',
            'name' => 'group',
            'title' => trans('general.group_by'),
            'icon' => 'folder',
            'values' => $this->groups,
            'selected' => 'category',
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }

    public function getPeriodField()
    {
        return [
            'type' => 'selectGroup',
            'name' => 'period',
            'title' => trans('general.period'),
            'icon' => 'calendar',
            'values' => [
                'monthly' => trans('general.monthly'),
                'quarterly' => trans('general.quarterly'),
                'yearly' => trans('general.yearly'),
            ],
            'selected' => 'quarterly',
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }

    public function getBasisField()
    {
        return [
            'type' => 'selectGroup',
            'name' => 'basis',
            'title' => trans('general.basis'),
            'icon' => 'file',
            'values' => [
                'accrual' => trans('general.accrual'),
                'cash' => trans('general.cash'),
            ],
            'selected' => 'accrual',
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }

    public function getChartField()
    {
        return [
            'type' => 'selectGroup',
            'name' => 'chart',
            'title' => trans_choice('general.charts', 1),
            'icon' => 'chart-pie',
            'values' => [
                '0' => trans('general.disabled'),
                'line' => trans('reports.charts.line'),
            ],
            'selected' => '0',
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }
}

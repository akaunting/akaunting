<?php

namespace App\Abstracts;

use Akaunting\Apexcharts\Chart;
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
use App\Models\Setting\Category;
use App\Traits\Charts;
use App\Traits\DateTime;
use App\Traits\SearchString;
use App\Traits\Translations;
use App\Utilities\Date;
use App\Utilities\Export as ExportHelper;
use Illuminate\Support\Str;

abstract class Report
{
    use Charts, DateTime, SearchString, Translations;

    public $model;

    public $default_name = '';

    public $category = 'reports.income_expense';

    public $icon = 'donut_small';

    public $type = 'detail';

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

    public $bar_formatter_type = 'money';

    public $donut_formatter_type = 'percent';

    public $chart = [
        'bar' => [
            'colors' => [
                '#6da252',
            ],

            'yaxis' => [
                'labels' => [
                    'formatter' => '',
                ],
            ],
        ],
        'donut' => [
            'yaxis' => [
                'labels' => [
                    'formatter' => '',
                ],
            ],
        ],
    ];

    public $column_name_width = 'report-column-name';
    public $column_value_width = 'report-column-value';

    public $row_tree_nodes = [];

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
        $this->setChartLabelFormatter();

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

    public function getCategoryDescription()
    {
        if (! empty($this->category_description)) {
            return trans($this->category_description);
        }

        return $this->findTranslation([
            $this->category . '_desc',
            $this->category . '_description',
            str_replace('general.', 'reports.', $this->category) . '_desc',
            str_replace('general.', 'reports.', $this->category) . '_description',
        ]);
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getCharts($table_key)
    {
        return [
            'bar'   => $this->getBarChart($table_key),
            'donut' => $this->getDonutChart($table_key),
        ];
    }

    public function getBarChart($table_key)
    {
        $chart = new Chart();

        if (empty($this->chart)) {
            return $chart;
        }

        $options = !empty($this->chart[$table_key]) ? $this->chart[$table_key]['bar'] : $this->chart['bar'];

        $chart->setType('bar')
            ->setOptions($options)
            ->setDefaultLocale($this->getDefaultLocaleOfChart())
            ->setLocales($this->getLocaleTranslationOfChart())
            ->setLabels(array_values($this->dates))
            ->setDataset($this->tables[$table_key], 'column', array_values($this->footer_totals[$table_key]));

        return $chart;
    }

    public function getDonutChart($table_key)
    {
        $chart = new Chart();

        if (empty($this->chart)) {
            return $chart;
        }

        $tmp_values = [];

        if (! empty($this->row_values[$table_key])) {
            foreach ($this->row_values[$table_key] as $id => $dates) {
                $tmp_values[$id] = 0;

                foreach ($dates as $date) {
                    $tmp_values[$id] += $date;
                }
            }
        }

        $tmp_values = collect($tmp_values)->sort()->reverse()->take(10)->all();

        $total = array_sum($tmp_values);
        $total = !empty($total) ? $total : 1;

        $group = $this->getSetting('group');

        $labels = $colors = $values = [];

        foreach ($tmp_values as $id => $value) {
            $labels[$id] = $this->row_names[$table_key][$id];

            $colors[$id] = ($group == 'category')
                            ? Category::withSubCategory()->find($id)?->colorHexCode
                            : $this->randHexColor();

            $values[$id] = round(($value * 100 / $total), 0);
        }

        $options = !empty($this->chart[$table_key]) ? $this->chart[$table_key]['donut'] : $this->chart['donut'];

        $chart->setType('donut')
            ->setOptions($options)
            ->setDefaultLocale($this->getDefaultLocaleOfChart())
            ->setLocales($this->getLocaleTranslationOfChart())
            ->setLabels(array_values($labels))
            ->setColors(array_values($colors))
            ->setDataset($this->tables[$table_key], 'donut', array_values($values));

        $chart->options['legend']['width'] = 105;
        $chart->options['legend']['position'] = 'right';

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
        return ExportHelper::toExcel(new Export($this->views[$this->type], $this), $this->model->name);
    }

    public function setColumnWidth()
    {
        if (!$period = $this->getSetting('period')) {
            return;
        }

        $width = '';

        switch ($period) {
            case 'quarterly':
                $width = 'w-2/12 col-2';
                break;
            case 'yearly':
                $width = 'w-4/12 col-4';
                break;
            case 'monthly':
                $width = 'col-1 w-20';
                break;
        }

        if (empty($width)) {
            return;
        }

        $this->column_name_width = $this->column_value_width = $width;
    }

    public function setChartLabelFormatter()
    {
        if (count($this->tables) > 1) {
            foreach ($this->tables as $table_key => $table) {
                if (empty($this->chart[$table_key])) {
                    continue;
                }

                $this->chart[$table_key]['bar']['yaxis']['labels']['formatter'] = $this->getChartLabelFormatter($this->bar_formatter_type);
                $this->chart[$table_key]['donut']['yaxis']['labels']['formatter'] = $this->getChartLabelFormatter($this->donut_formatter_type);
            }
        } else {
            $this->chart['bar']['yaxis']['labels']['formatter'] = $this->getChartLabelFormatter($this->bar_formatter_type);
            $this->chart['donut']['yaxis']['labels']['formatter'] = $this->getChartLabelFormatter($this->donut_formatter_type);
        }
    }

    public function setYear()
    {
        $this->year = $this->getSearchStringValue('year', Date::now()->year);
    }

    public function setViews()
    {
        $this->views = [
            'show'                      => 'components.reports.show',
            'print'                     => 'components.reports.print',
            'filter'                    => 'components.reports.filter',

            'detail'                    => 'components.reports.detail',
            'detail.content.header'     => 'components.reports.detail.content.header',
            'detail.content.footer'     => 'components.reports.detail.content.footer',
            'detail.table'              => 'components.reports.detail.table',
            'detail.table.header'       => 'components.reports.detail.table.header',
            'detail.table.body'         => 'components.reports.detail.table.body',
            'detail.table.row'          => 'components.reports.detail.table.row',
            'detail.table.footer'       => 'components.reports.detail.table.footer',

            'summary'                   => 'components.reports.summary',
            'summary.content.header'    => 'components.reports.summary.content.header',
            'summary.content.footer'    => 'components.reports.summary.content.footer',
            'summary.table'             => 'components.reports.summary.table',
            'summary.table.header'      => 'components.reports.summary.table.header',
            'summary.table.body'        => 'components.reports.summary.table.body',
            'summary.table.row'         => 'components.reports.summary.table.row',
            'summary.table.footer'      => 'components.reports.summary.table.footer',
            'summary.chart'             => 'components.reports.summary.chart',
        ];
    }

    public function setTables()
    {
        $this->tables = [
            'default' => trans_choice('general.totals', 1),
        ];
    }

    public function setDates()
    {
        if (! $period = $this->getSetting('period')) {
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

            foreach ($this->tables as $table_key => $table_name) {
                $this->footer_totals[$table_key][$date] = 0;
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

    public function getBasis()
    {
        return $this->getSearchStringValue('basis', $this->getSetting('basis'));
    }

    public function getFields()
    {
        return [
            $this->getGroupField(),
            $this->getPeriodField(),
            $this->getBasisField(),
        ];
    }

    public function getGroupField()
    {
        $this->setGroups();

        return [
            'type' => 'select',
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
            'type' => 'select',
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
            'type' => 'select',
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

    public function randHexColorPart(): string
    {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    public function randHexColor(): string
    {
        return '#' . $this->randHexColorPart() . $this->randHexColorPart() . $this->randHexColorPart();
    }
}

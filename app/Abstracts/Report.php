<?php

namespace App\Abstracts;

use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportFilterApplying;
use App\Events\Common\ReportGroupApplying;
use App\Events\Common\ReportGroupShowing;
use App\Exports\Common\Reports as Export;
use App\Models\Common\Report as Model;
use App\Traits\Charts;
use App\Traits\DateTime;
use App\Utilities\Chartjs;
use Date;
use Illuminate\Support\Str;

abstract class Report
{
    use Charts, DateTime;

    public $model;

    public $default_name = '';

    public $category = 'reports.income_expense';

    public $icon = 'fa fa-chart-pie';

    public $year;

    public $views = [];

    public $tables = [];

    public $dates = [];

    public $rows = [];

    public $totals = [];

    public $filters = [];

    public $indents = [
        'table_header' => '0px',
        'table_rows' => '0px',
    ];

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

    public function __construct(Model $model = null, $get_totals = true)
    {
        $this->setGroups();

        if (!$model) {
            return;
        }

        $this->model = $model;

        $this->setYear();
        $this->setViews();
        $this->setTables();
        $this->setDates();
        $this->setFilters();
        $this->setRows();

        if ($get_totals) {
            $this->getTotals();
        }
    }

    abstract public function getTotals();

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

    public function getTotal()
    {
        $sum = 0;

        foreach ($this->totals as $total) {
            $sum += is_array($total) ? array_sum($total) : $total;
        }

        $total = money($sum, setting('default.currency'), true);

        return $total;
    }

    public function getTableRowList()
    {
        $group_prl = Str::plural($this->model->settings->group);

        if ($group_filter = request($group_prl)) {
            $rows = collect($this->filters[$group_prl])->filter(function ($value, $key) use ($group_filter) {
                return in_array($key, $group_filter);
            });
        } else {
            $rows = $this->filters[$group_prl];
        }

        return $rows;
    }

    public function getChart()
    {
        $chart = new Chartjs();

        $config = $this->chart[$this->model->settings->chart];

        $default_options = $this->getLineChartOptions();

        $options = array_merge($default_options, (array) $config['options']);

        $chart->type($this->model->settings->chart)
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
            foreach ($this->totals as $total) {
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
        return \Excel::download(new Export($this->views['content'], $this), $this->model->name . '.xlsx');
    }

    public function setYear()
    {
        $this->year = request('year', Date::now()->year);
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
            'default' => 'default'
        ];
    }

    public function setDates()
    {
        $function = 'sub' . ucfirst(str_replace('ly', '', $this->model->settings->period));

        $start = $this->getFinancialStart()->copy()->$function();

        for ($j = 1; $j <= 12; $j++) {
            switch ($this->model->settings->period) {
                case 'yearly':
                    $start->addYear();

                    $date = $this->getFormattedDate($start);

                    $this->dates[$j] = $date;

                    foreach ($this->tables as $table) {
                        $this->totals[$table][$date] = 0;
                    }

                    $j += 11;

                    break;
                case 'quarterly':
                    $start->addQuarter();

                    $date = $this->getFormattedDate($start);

                    $this->dates[$j] = $date;

                    foreach ($this->tables as $table) {
                        $this->totals[$table][$date] = 0;
                    }

                    $j += 2;

                    break;
                default:
                    $start->addMonth();

                    $date = $this->getFormattedDate($start);

                    $this->dates[$j] = $date;

                    foreach ($this->tables as $table) {
                        $this->totals[$table][$date] = 0;
                    }

                    break;
            }
        }
    }

    public function setFilters()
    {
        event(new ReportFilterShowing($this));
    }

    public function setGroups()
    {
        $this->groups = [];

        event(new ReportGroupShowing($this));
    }

    public function setRows()
    {
        $list = $this->getTableRowList();

        foreach ($this->dates as $date) {
            foreach ($this->tables as $table) {
                foreach ($list as $id => $name) {
                    $this->rows[$table][$id][$date] = 0;
                }
            }
        }
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default')
    {
        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $date = $this->getFormattedDate(Date::parse($item->$date_field));

            $id_field = $this->model->settings->group . '_id';

            if (!isset($this->rows[$table][$item->$id_field]) ||
                !isset($this->rows[$table][$item->$id_field][$date]) ||
                !isset($this->totals[$table][$date]))
            {
                continue;
            }

            $amount = $item->getAmountConvertedToDefault();

            if (!$check_type) {
                $this->rows[$table][$item->$id_field][$date] += $amount;

                $this->totals[$table][$date] += $amount;
            } else {
                $type = (($item->getTable() == 'invoices') || (($item->getTable() == 'transactions') && ($item->type == 'income'))) ? 'income' : 'expense';

                if ($type == 'income') {
                    $this->rows[$table][$item->$id_field][$date] += $amount;

                    $this->totals[$table][$date] += $amount;
                } else {
                    $this->rows[$table][$item->$id_field][$date] -= $amount;

                    $this->totals[$table][$date] -= $amount;
                }
            }
        }
    }

    public function applyFilters($model, $args = [])
    {
        event(new ReportFilterApplying($this, $model, $args));

        return $model;
    }

    public function applyGroups($model, $args = [])
    {
        event(new ReportGroupApplying($this, $model, $args));

        return $model;
    }

    public function getFormattedDate($date)
    {
        switch ($this->model->settings->period) {
            case 'yearly':
                $i = $date->copy()->format($this->getYearlyDateFormat());
                break;
            case 'quarterly':
                $start = $date->copy()->startOfQuarter()->format($this->getQuarterlyDateFormat());
                $end = $date->copy()->endOfQuarter()->format($this->getQuarterlyDateFormat());

                $i = $start . '-' . $end;
                break;
            default:
                $i = $date->copy()->format($this->getMonthlyDateFormat());
                break;
        }

        return $i;
    }

    public function getUrl($action = 'print')
    {
        $print_url = 'common/reports/' . $this->model->id . '/' . $action . '?year='. $this->year;

        collect(request('accounts'))->each(function($item) use(&$print_url) {
            $print_url .= '&accounts[]=' . $item;
        });

        collect(request('customers'))->each(function($item) use(&$print_url) {
            $print_url .= '&customers[]=' . $item;
        });

        collect(request('categories'))->each(function($item) use(&$print_url) {
            $print_url .= '&categories[]=' . $item;
        });

        return $print_url;
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

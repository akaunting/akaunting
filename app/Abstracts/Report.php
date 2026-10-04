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
use App\Events\Report\TotalCalculating;
use App\Events\Report\TotalCalculated;
use App\Exports\Common\Reports as Export;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Common\Report as Model;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use App\Models\Setting\Category;
use App\Models\Setting\Tax;
use App\Traits\Charts;
use App\Traits\DateTime;
use App\Traits\SearchString;
use App\Traits\Translations;
use App\Utilities\Date;
use App\Utilities\Export as ExportHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class Report
{
    use Charts, DateTime, SearchString, Translations;

    /**
     * The keys of $filters that describe the chips instead of being one.
     */
    public const FILTER_METADATA = ['keys', 'names', 'types', 'routes', 'multiple', 'defaults', 'operators'];

    public $model;

    public $default_name = '';

    public $category = 'reports.income_expense';

    public $icon = 'donut_small';

    public $type = 'detail';

    public $has_money = true;

    public $group;

    public $groups = [];

    public $year;

    public $views = [];

    public $tables = [];

    public $dates = [];

    public $row_names = [];

    public $row_values = [];

    public $footer_totals = [];

    public $filters = [];

    /**
     * The chips as this load applied them, [[label, value], ...], which Print, PDF and Excel list above the report.
     * Filled by load() rather than when rendering, as a queued Excel export renders without the request.
     */
    public $applied_filters = [];

    public $loaded = false;

    /**
     * Whether the report pages may serve this report from the cache (App\Utilities\ReportCache).
     * Null caches it only when it renders the core show view, which carries the refresh button;
     * false never does, for output that depends on something outside the database, such as an API.
     */
    public $cacheable = null;

    /**
     * When the served copy was built, or null when it was loaded live and not cached.
     */
    public $cached_at = null;

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
        $this->setAppliedFilters();
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
        $dataset_name = $this->tables[$table_key] ?? trans_choice('general.totals', 1);
        $dataset_values = $this->footer_totals[$table_key] ?? [];

        $chart->setType('bar')
            ->setOptions($options)
            ->setDefaultLocale($this->getDefaultLocaleOfChart())
            ->setLocales($this->getLocaleTranslationOfChart())
            ->setLabels(array_values($this->dates))
            ->setDataset($dataset_name, 'column', array_values($dataset_values));

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

        $group = $this->getGroup();

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
        return view($this->views['show'], ['print' => false])->with('class', $this);
    }

    public function print()
    {
        return view($this->views['print'], ['print' => true])->with('class', $this);
    }

    public function array(): array
    {
        $data = [];

        $group = Str::plural($this->group ?? $this->getGroup());

        foreach ($this->tables as $table_key => $table_name) {
            if (! isset($this->row_values[$table_key])) {
                continue;
            }

            foreach ($this->row_values[$table_key] as $key => $values) {
                if (empty($this->row_names[$table_key][$key])) {
                    continue;
                }

                if ($this->has_money) {
                    $values = array_map(fn($value) => money($value)->format(), $values);
                }

                $data[$table_key][$group][$this->row_names[$table_key][$key]] = $values;
            }

            $footer_totals = $this->footer_totals[$table_key];

            if ($this->has_money) {
                $footer_totals = array_map(fn($value) => money($value)->format(), $footer_totals);
            }

            $data[$table_key]['totals'] = $footer_totals;
        }

        return $data;
    }

    public function pdf()
    {
        $view = view($this->views['print'], ['print' => true])->with('class', $this)->render();

        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $pdf->setPaper('A4', 'landscape');

        $file_name = $this->model->name . ' - ' . company()->name . '.pdf';

        return $pdf->download($file_name);
    }

    public function export()
    {
        return ExportHelper::toExcel(new Export($this->views[$this->type], $this), $this->model->name);
    }

    public function setColumnWidth()
    {
        if (! $period = $this->getPeriod()) {
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
            case 'weekly':
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
        $this->year = request()->filled('start_date') ? Date::parse(request('start_date'))->year : Date::now()->year;
    }

    public function setViews()
    {
        $this->views = [
            'show'                      => 'components.reports.show',
            'print'                     => 'components.reports.print',
            'filter'                    => 'components.reports.filter',
            'applied'                   => 'components.reports.applied',

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
        if (! $period = $this->getPeriod()) {
            return;
        }

        [$start, $end] = $this->getStartAndEndDates($this->year);

        $counter = match ($period) {
            'weekly'    => $end->diffInWeeks($start),
            'quarterly' => $end->diffInQuarters($start),
            'yearly'    => $end->diffInYears($start),
            default     => $end->diffInMonths($start),
        };

        for ($j = 0; $j <= $counter; $j++) {
            $date = $this->getPeriodicDate($start, $this->getPeriod(), $this->year);

            $this->dates[] = $date;

            foreach ($this->tables as $table_key => $table_name) {
                $this->footer_totals[$table_key][$date] = 0;
            }

            match ($period) {
                'weekly'    => $start->addWeek(),
                'quarterly' => $start->addQuarter(),
                'yearly'    => $start->addYear(),
                default     => $start->addMonth(),
            };
        }
    }

    public function setFilters()
    {
        event(new FilterShowing($this));
    }

    public function setAppliedFilters(): void
    {
        $this->applied_filters = $this->getAppliedFilters();
    }

    /**
     * The chips the listeners registered, name => values, without the metadata kept beside them in $filters.
     */
    public function getFilterChips(): array
    {
        return array_filter(
            array: array_diff_key($this->filters, array_flip(static::FILTER_METADATA)),
            callback: 'is_array',
        );
    }

    /**
     * The search string key a chip filters on: the one its listener set, contact_id for customers and vendors,
     * otherwise its singular name with _id, so categories filters category_id.
     */
    public function getFilterKey(string $name): string
    {
        if (! empty($this->filters['keys'][$name])) {
            return $this->filters['keys'][$name];
        }

        if (in_array($name, ['customers', 'vendors'])) {
            return 'contact_id';
        }

        return Str::singular($name) . '_id';
    }

    /**
     * The chip's label: the one its listener set, otherwise the reports or general translation of its name.
     */
    public function getFilterLabel(string $name): string
    {
        if (! empty($this->filters['names'][$name])) {
            return $this->filters['names'][$name];
        }

        $key = (trans('reports.' . $name) != 'reports.' . $name) ? 'reports.' . $name : 'general.' . $name;
        $label = trans($key);

        if (! is_string($label)) {
            return $name;
        }

        return str_contains($label, '|')
            ? trans_choice($key, 1)
            : $label;
    }

    /**
     * Each chip with the value the report used: the search string's, else the chip's default, else the saved
     * setting; a filter nobody chose is left out. Then the saved preferences that have no chip and differ from
     * the report's default, and what else the search string narrows the data by, such as free text.
     */
    public function getAppliedFilters(): array
    {
        $fields = collect($this->getFields())
            ->filter(fn ($field) => is_array($field) && ! empty($field['name']))
            ->keyBy('name')
            ->all();

        $keys = [];
        $rows = [];

        foreach ($this->getFilterChips() as $name => $values) {
            $key = $this->getFilterKey($name);
            $keys[] = $key;

            $value = ($key == 'date_range')
                ? $this->getAppliedDateRange()
                : $this->getAppliedFilterValue($name, $key, $values, $fields[$key] ?? null);

            if ($value === '') {
                continue;
            }

            // Dates first, then the chips of one value, then the lists
            $order = match (true) {
                ($key == 'date_range') || $this->isDateValue((string) array_key_first($values)) => 0,
                ! $this->isMultipleFilter($name) => 1,
                default => 2,
            };

            $rows[$order][] = [$this->getFilterLabel($name), $value];
        }

        foreach (array_diff_key($fields, array_flip($keys)) as $name => $field) {
            $value = $this->getSetting($name, $field['selected'] ?? '');

            if (! is_scalar($value) || ((string) $value === (string) ($field['selected'] ?? ''))) {
                continue;
            }

            $rows[3][] = [$field['title'] ?? $name, $this->getAppliedValueText((string) $value, $field['values'][$value] ?? $value)];
        }

        // year: is the dead token old See performance links carry, which no report reads
        if ($terms = $this->getAppliedSearchTerms(array_merge($keys, array_keys($fields), ['year']))) {
            $rows[4][] = [trans('general.search'), $terms];
        }

        ksort($rows);

        return array_merge(...$rows);
    }

    /**
     * The window the report covers, as dates only: the chip names its presets after the window requested rather
     * than today, so a past quarter's dates would be named This Quarter.
     */
    protected function getAppliedDateRange(): string
    {
        [$start, $end] = $this->getStartAndEndDates($this->year);

        return trans('reports.applied_filters.dates', [
            'start' => company_date($start),
            'end' => company_date($end)],
        );
    }

    /**
     * The names of the value a chip applied, after "is not" when the search string excluded it.
     */
    protected function getAppliedFilterValue(string $name, string $key, array $values, ?array $field): string
    {
        [$value, $exclude] = $this->getAppliedFilterChoice($name, $key, $values, $field);

        if ($value === '') {
            return '';
        }

        // The whole value first, as one value can hold a comma: Unpaid is "sent,viewed,partial"
        if (array_key_exists($value, $values)) {
            $text = $this->getAppliedValueText($value, $values[$value]);
        } else {
            $ids = explode(',', $value);
            $names = $this->getFilterValueNames($key, array_diff($ids, array_keys($values)));

            $text = implode(', ', array_map(
                fn ($id) => $this->getAppliedValueText($id, $values[$id] ?? $names[$id] ?? $id),
                $ids,
            ));
        }

        return $exclude
            ? trans('reports.applied_filters.excluded', ['values' => $text])
            : $text;
    }

    /**
     * The value a chip applied, [value, excluded]: the search string's, else the chip's default, else, for a chip
     * of one value, the report's saved setting of the same name. Empty when a filter has nothing chosen.
     */
    protected function getAppliedFilterChoice(
        string $name,
        string $key,
        array $values,
        ?array $field,
    ): array {
        $search = request('search');
        $search = ' ' . (is_string($search) ? $search : '') . ' ';

        // "Is not" only where the chip declares that its report honours it; elsewhere a report reads the value alone
        $excludable = ($this->filters['operators'][$name]['not_equal'] ?? false) === true;

        // A value holding a space spans two terms of the search string, as Overdue's "partial,sent,viewed
        // due_at<=today" does, so it is looked for whole, the longest first
        $spanning = array_filter(array_map('strval', array_keys($values)), fn ($value) => str_contains($value, ' '));
        usort($spanning, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($spanning as $value) {
            if (str_contains($search, ' ' . $key . ':' . $value . ' ')) {
                return [
                    $value,
                    $excludable && str_contains($search, ' not ' . $key . ':' . $value . ' '),
                ];
            }
        }

        // A chip that always has a value, as a setting does, takes the search string's only where the report would
        $setting = ! $this->isMultipleFilter($name) && (isset($this->filters['defaults'][$name]) || $field);

        $value = $this->getSearchStringValue($key);

        if (is_string($value) && ($value !== '') && (! $setting || $this->isFilterValue($value, $values))) {
            return [
                $value,
                $excludable && ($this->getSearchStringOperator($key) == '!='),
            ];
        }

        if (! empty($this->filters['defaults'][$name])) {
            return [
                (string) $this->filters['defaults'][$name],
                false,
            ];
        }

        $value = $field ? $this->getSetting($key, $field['selected'] ?? '') : '';

        return [
            ($setting && is_scalar($value)) ? (string) $value : '',
            false,
        ];
    }

    /**
     * Whether a chip of one value takes the value: it is one of the chip's, or a date for a chip of dates.
     */
    protected function isFilterValue(string $value, array $values): bool
    {
        return array_key_exists($value, $values)
            || ($this->isDateValue((string) array_key_first($values)) && $this->isDateValue($value));
    }

    /**
     * What else the search string narrows the data by, word for word: free text and the terms of no chip or
     * setting of the report, of which the given keys are.
     */
    protected function getAppliedSearchTerms(array $keys): string
    {
        $search = request('search');

        if (! is_string($search) || (trim($search) === '')) {
            return '';
        }

        // A chip's value that spans two terms, as Overdue's does, belongs to the chip
        foreach ($this->getFilterChips() as $name => $values) {
            foreach (array_keys($values) as $value) {
                if (! str_contains((string) $value, ' ')) {
                    continue;
                }

                $term = $this->getFilterKey($name) . ':' . $value;
                $search = str_replace(['not ' . $term, $term], '', $search);
            }
        }

        preg_match_all('/"[^"]*"|\S+/', $search, $matches);

        $terms = [];
        $negated = false;

        foreach ($matches[0] as $term) {
            if ($term === 'not') {
                $negated = true;

                continue;
            }

            $key = preg_match('/^(\w+)(>=|<=|>|<|=|:)/', $term, $parts) ? $parts[1] : null;

            if (! in_array($key, $keys, true)) {
                $terms[] = ($negated ? 'not ' : '') . $term;
            }

            $negated = false;
        }

        // AddSearchString appends the request's other keys on each query, so a term can repeat
        return implode(' ', array_unique($terms));
    }

    /**
     * A value's label, with the date when the value is one, as for an as-of preset such as End of Last Month.
     */
    protected function getAppliedValueText(string $value, mixed $label): string
    {
        $label = is_scalar($label) ? (string) $label : $value;

        if (! $this->isDateValue($value)) {
            return $label;
        }

        $date = company_date($value);

        return in_array($label, [$value, $date])
            ? $date
            : trans('reports.applied_filters.named_date', ['name' => $label, 'date' => $date]);
    }

    /**
     * The names of chosen ids a chip's values miss, since a chip lists only the first values of a long list.
     */
    protected function getFilterValueNames(string $key, array $ids): array
    {
        $ids = array_filter($ids, 'is_numeric');

        $query = match ($key) {
            'account_id'                        => Account::query(),
            'category_id', 'item_category_id'   => Category::query()->withSubCategory(),
            'contact_id'                        => Contact::query(),
            'item_id'                           => Item::query(),
            'tax_id'                            => Tax::query(),
            default                             => null,
        };

        if (empty($ids) || is_null($query)) {
            return [];
        }

        return $query->whereIn('id', $ids)->pluck('name', 'id')->all();
    }

    protected function isMultipleFilter(string $name): bool
    {
        return ! empty($this->filters['multiple'][$name]) || ! empty($this->filters['operators'][$name]['multiple']);
    }

    protected function isDateValue(string $value): bool
    {
        return preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $parts) && checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1]);
    }

    public function setGroups()
    {
        event(new GroupShowing($this));
    }

    public function setRows()
    {
        event(new RowsShowing($this));
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default', $with_tax = true)
    {
        event(new TotalCalculating($this, $items, $date_field, $check_type, $table, $with_tax));

        $group_field = $this->getGroup() . '_id';

        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $date = $this->getPeriodicDate(Date::parse($item->$date_field), $this->getPeriod(), $this->year);

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

        event(new TotalCalculated($this, $items, $date_field, $check_type, $table, $with_tax));
    }

    public function setArithmeticTotals($items, $date_field, $operator = 'add', $table = 'default', $amount_field = 'amount')
    {
        $group_field = $this->getGroup() . '_id';

        $function = $operator . 'ArithmeticAmount';

        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $date = $this->getPeriodicDate(Date::parse($item->$date_field), $this->getPeriod(), $this->year);

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
        if ($amount == 0) {
            throw new \InvalidArgumentException('Division by zero is not allowed');
        }

        $current = $current / $amount;
    }

    public function modArithmeticAmount(&$current, $amount)
    {
        if ($amount == 0) {
            throw new \InvalidArgumentException('Modulo by zero is not allowed');
        }

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

    public function flattenDocumentItems(Collection $documents): Collection
    {
        return $documents->flatMap(fn (Document $d) =>
            $d->items->map(function (DocumentItem $di) use ($d) {
                $proxy = clone $di;
                $proxy->type = $d->type;
                $proxy->document_number = $d->document_number;
                $proxy->status = $d->status;
                $proxy->issued_at = $d->issued_at;
                $proxy->due_at = $d->due_at;
                $proxy->currency_code = $d->currency_code;
                $proxy->currency_rate = $d->currency_rate;
                $proxy->amount = $di->total;
                $proxy->contact_id = $d->contact_id;

                return $proxy;
            })
        );
    }

    public function getUrl($action = 'print')
    {
        $url = company_id() . '/common/reports/' . $this->model->id . '/' . $action;

        $parameters = array_filter(
            request()->all(),
            static function ($value, $key) {
                // Skip Laravel internal params
                if (in_array($key, ['_token', '_method'])) {
                    return false;
                }

                return is_null($value) || is_scalar($value) || is_array($value);
            },
            ARRAY_FILTER_USE_BOTH
        );

        $query = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);

        if (!empty($query)) {
            $url .= '?' . $query;
        }

        return $url;
    }

    public function getSetting($name, $default = '')
    {
        return $this->model->settings->$name ?? $default;
    }

    protected function getDefaultFieldSelection(array $field, string $fallback = ''): string
    {
        return $field['selected'] ?? $fallback;
    }

    public function getFieldValue(string $name, ?string $field_function = null): mixed
    {
        $function = $field_function ?: 'get' . ucfirst($name) . 'Field';

        return $this->getSearchStringValue(
            name: $name,
            default: $this->getSetting(
                name: $name,
                default: method_exists($this, $function) ? $this->getDefaultFieldSelection($this->{$function}()) : '',
            ),
        );
    }

    public function getBasis()
    {
        return $this->getFieldValue('basis');
    }

    public function getPeriod()
    {
        return $this->getFieldValue('period');
    }

    public function getGroup()
    {
        return $this->getFieldValue('group');
    }

    public function getDiscount()
    {
        return $this->getFieldValue('discount');
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
                'weekly' => trans('general.weekly'),
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

    // @deprecated 3.1
    public function getFormattedDate($date)
    {
        return $this->getPeriodicDate($date, $this->getPeriod(), $this->year);
    }
}

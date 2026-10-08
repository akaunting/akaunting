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
use App\Models\Auth\Permission;
use App\Models\Auth\User;
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
use App\Traits\Permissions;
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
    public const FILTER_METADATA = ['keys', 'names', 'types', 'routes', 'multiple', 'defaults', 'operators', 'resets', 'models', 'presets'];

    /**
     * The order of the report options bar's strip, display options and filters, by search string key; keys not
     * listed follow in the order the listeners registered them.
     */
    protected const FILTER_BAR_ORDER = [
        'strip' => ['date_field', 'date_range', 'as_of', 'basis', 'ageing_by', 'discount', 'period', 'group', 'mode', 'group_by'],
        'display' => ['sort_by', 'direction'],
        'filters' => ['sources', 'contact_id', 'status', 'category_id', 'item_category_id', 'account_id', 'item_id', 'tax_id', 'warehouse_id', 'currency_code', 'amount'],
    ];

    /**
     * The settings that pick how the figures are worked out, which the bar shows as a switch.
     */
    protected const FILTER_BAR_ACCOUNTING = ['basis', 'ageing_by', 'discount'];

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
        // Set first, so the GroupShowing listeners of setGroups() can tell which report row this is
        $this->model = $model;

        $this->setGroups();

        if (!$model || !$load_data) {
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

    /**
     * The year getFinancialYear() and the period columns take: the one the financial year holding the start date,
     * or today, is named after. A financial year denoted by its end that starts after 1 January is named after the
     * calendar year it ends in, so the calendar year of the date would give the year before.
     */
    public function setYear()
    {
        $date = request()->filled('start_date') ? Date::parse(request('start_date')) : Date::now();

        $this->year = $this->getFinancialYearName($date);
    }

    public function setViews()
    {
        $this->views = [
            'show'                      => 'components.reports.show',
            'print'                     => 'components.reports.print',
            'filter'                    => 'components.reports.filter-bar',
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

        $key = $this->getFilterTranslationKey($name);
        $label = trans($key);

        if (! is_string($label)) {
            return $name;
        }

        return str_contains($label, '|')
            ? trans_choice($key, 1)
            : $label;
    }

    /**
     * The translation of a chip the listener gave no label: reports.<name>, else general.<name>.
     */
    protected function getFilterTranslationKey(string $name): string
    {
        return (trans('reports.' . $name) != 'reports.' . $name) ? 'reports.' . $name : 'general.' . $name;
    }

    /**
     * Each chip with the value the report used: the search string's, else the chip's default, else the saved
     * setting; a filter nobody chose is left out. Then the settings that have no chip and differ from the report's
     * default, as this run applied those the options bar changes per run (placement) and as saved otherwise, and
     * what else the search string narrows the data by, such as free text.
     */
    public function getAppliedFilters(): array
    {
        $fields = $this->getFieldsByName();

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
            $values = (isset($field['values']) && is_array($field['values'])) ? $field['values'] : [];

            // A field the options bar changes per run prints the value of this run, as the bar shows it
            $value = (in_array($field['placement'] ?? null, ['strip', 'display'], true) && ! empty($values))
                ? $this->getFilterBarChoice($name, $name, $values, $field)
                : $this->getSetting($name, $field['selected'] ?? '');

            if (! is_scalar($value) || ((string) $value === (string) ($field['selected'] ?? ''))) {
                continue;
            }

            $rows[3][] = [$field['title'] ?? $name, $this->getAppliedValueText((string) $value, $values[$value] ?? $value)];
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
            $names = $this->getFilterValueNames($name, $key, array_diff($ids, array_keys($values)));

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
     * The value a chip applied, [value, excluded]: a single date as its readers parse it; else the search string's,
     * else the chip's default, else, for a chip of one value, the report's saved setting of the same name. Empty
     * when a filter has nothing chosen.
     */
    protected function getAppliedFilterChoice(
        string $name,
        string $key,
        array $values,
        ?array $field,
    ): array {
        // A single date is the one its readers parse, as_of:yesterday included, as the options bar shows it
        if (! $this->isMultipleFilter($name) && (($key == 'as_of') || $this->hasOnlyDateValues($values))) {
            return [
                $this->getFilterBarDate($name, $key),
                false,
            ];
        }

        $search = request('search');
        $search = ' ' . (is_string($search) ? $search : '') . ' ';

        // "Is not" only where the chip declares that its report honours it; elsewhere a report reads the value alone
        $excludable = ($this->filters['operators'][$name]['not_equal'] ?? false) === true;

        foreach ($this->getSpanningValues($values) as $value) {
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
     * A chip's values holding a space, longest first: such a value spans two terms of the search string, as
     * Overdue's "partial,sent,viewed due_at<=today" does, so it is looked for whole.
     */
    protected function getSpanningValues(array $values): array
    {
        $spanning = array_filter(array_map('strval', array_keys($values)), fn ($value) => str_contains($value, ' '));

        usort($spanning, fn ($a, $b) => strlen($b) <=> strlen($a));

        return $spanning;
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
        return implode(' ', $this->getOtherSearchTerms($keys));
    }

    /**
     * The search string's terms of no chip or setting of the report, of which the given keys are, word for word
     * and each once: free text and the like, which narrow the data too. A "not" stays with the term after it.
     */
    protected function getOtherSearchTerms(array $keys): array
    {
        $search = request('search');

        if (! is_string($search) || (trim($search) === '')) {
            return [];
        }

        // A chip's value that spans two terms, as Overdue's does, is one term, longest first
        $spanning = [];

        foreach ($this->getFilterChips() as $name => $values) {
            foreach ($this->getSpanningValues($values) as $value) {
                $spanning[] = explode(' ', $this->getFilterKey($name) . ':' . $value);
            }
        }

        usort($spanning, fn ($a, $b) => count($b) <=> count($a));

        preg_match_all('/"[^"]*"|\S+/', $search, $matches);

        $words = $matches[0];
        $terms = [];
        $negated = false;

        for ($i = 0; $i < count($words); $i += $length) {
            $term = $words[$i];
            $length = 1;

            foreach ($spanning as $span) {
                if (array_slice($words, $i, count($span)) === $span) {
                    $term = implode(' ', $span);
                    $length = count($span);

                    break;
                }
            }

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
        return array_values(array_unique($terms));
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
     * The names of chosen ids a chip's values miss, since a chip lists only the first values of a long list: from
     * the model the chip's listener names in filters['models'], else from the model of a core key.
     */
    protected function getFilterValueNames(string $name, string $key, array $ids): array
    {
        $ids = array_filter($ids, 'is_numeric');

        if (empty($ids)) {
            return [];
        }

        if (! empty($this->filters['models'][$name])) {
            return $this->getFilterModelNames((array) $this->filters['models'][$name], $ids);
        }

        $query = match ($key) {
            'account_id'                        => Account::query(),
            'category_id', 'item_category_id'   => Category::query()->withSubCategory(),
            'contact_id'                        => Contact::query(),
            'item_id'                           => Item::query(),
            'tax_id'                            => Tax::query(),
            default                             => null,
        };

        if (is_null($query)) {
            return [];
        }

        return $query->whereIn('id', $ids)->pluck('name', 'id')->all();
    }

    /**
     * Names from a listener's [model class, label attribute]. The label can be an accessor, so the models are
     * loaded; users are not scoped to a company, so they are looked up among the current company's.
     */
    protected function getFilterModelNames(array $model, array $ids): array
    {
        [$class, $attribute] = array_pad(array_values($model), 2, 'name');

        if (! is_string($class) || ! class_exists($class)) {
            return [];
        }

        $query = is_a($class, User::class, true)
            ? company()->users()->whereIn('users.id', $ids)
            : $class::query()->whereIn('id', $ids);

        return $query->get()->mapWithKeys(fn ($model) => [$model->id => $model->{$attribute}])->all();
    }

    protected function isMultipleFilter(string $name): bool
    {
        return ! empty($this->filters['multiple'][$name]) || ! empty($this->filters['operators'][$name]['multiple']);
    }

    protected function isDateValue(string $value): bool
    {
        return preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value, $parts) && checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1]);
    }

    /**
     * The report's settings fields, by name.
     */
    protected function getFieldsByName(): array
    {
        return collect($this->getFields())
            ->filter(fn ($field) => is_array($field) && ! empty($field['name']))
            ->keyBy('name')
            ->all();
    }

    /**
     * The definition the report options bar renders (components.reports.filter-bar): the settings that always have
     * a value in a strip, the data filters, the display options and the saved preferences, each with the value this
     * request applied, and the search terms the bar keeps word for word. Built when the bar renders, so a cached
     * report gets it from the replayed request.
     */
    public function getFilterControls(): array
    {
        $fields = $this->getFieldsByName();
        $chips = $this->getFilterChips();
        $pairs = $this->getFilterBarAmountPairs($chips);

        $sections = ['strip' => [], 'display' => [], 'filters' => []];
        $keys = [];

        // year: is the dead token old See performance links carry, which no report reads
        $owned = ['year'];

        foreach (array_keys($chips) as $position => $name) {
            $key = $this->getFilterKey($name);
            $keys[] = $key;

            // The maximum of a number range belongs to its minimum's field
            if (in_array($key, $pairs, true)) {
                continue;
            }

            [$section, $control] = isset($pairs[$key])
                ? ['filters', $this->getFilterBarAmount($key, $pairs[$key])]
                : $this->getFilterBarControl($name, $key, $chips[$name], $fields[$key] ?? null);

            if (is_null($control)) {
                continue;
            }

            $owned = array_merge($owned, $control['owns']);
            unset($control['owns']);

            $sections[$section][] = [$position, $control];
        }

        // The settings that have no chip: editable where the report opts in, otherwise listed as they are saved
        $readonly = [];
        $position = count($chips);

        foreach (array_diff_key($fields, array_flip($keys)) as $name => $field) {
            $placement = $field['placement'] ?? null;
            $values = (isset($field['values']) && is_array($field['values'])) ? $field['values'] : [];

            if (in_array($placement, ['strip', 'display'], true) && ! empty($values)) {
                $sections[$placement][] = [$position++, [
                    'key' => $name,
                    'kind' => $this->getFilterBarSettingKind($name, $placement, $values),
                    'label' => $field['title'] ?? $name,
                    'options' => $this->getFilterBarOptions($values),
                    'value' => $this->getFilterBarChoice($name, $name, $values, $field),
                ]];

                $owned[] = $name;

                continue;
            }

            if ($row = $this->getFilterBarSavedPreference($name, $field, $values)) {
                $readonly[] = $row;
            }
        }

        foreach ($sections as $section => $controls) {
            $sections[$section] = $this->sortFilterBarControls($section, $controls);
        }

        return [
            'id' => $this->model->id,
            'action' => route('reports.show', $this->model->id),
            'edit_url' => route('reports.edit', $this->model->id),
            'has_query' => ! empty(request()->server('QUERY_STRING')),
            'can_edit' => (bool) user()?->can('update-common-reports'),
            'locale' => language()->getShortCode(),
            'date_format' => company_date_format(),
            'strip' => $sections['strip'],
            'display' => $sections['display'],
            'filters' => $sections['filters'],
            'readonly' => $readonly,
            'extras' => $this->getOtherSearchTerms($owned),
            'summary' => $this->getFilterBarSummary($sections, $readonly),
            'texts' => $this->getFilterBarTexts(),
        ];
    }

    /**
     * A chip as the bar shows it, [section, control]: a time window, a setting of the strip or of the display
     * options, or a data filter. The control lists the search string keys it writes, under owns.
     */
    protected function getFilterBarControl(string $name, string $key, array $values, ?array $field): array
    {
        $label = $this->getFilterLabel($name);
        $multiple = $this->isMultipleFilter($name);

        if ($key == 'date_range') {
            [$start, $end] = $this->getFilterBarDates();

            return ['strip', [
                'key' => $key,
                'kind' => 'range',
                'label' => $label,
                'presets' => $this->getFilterBarRangePresets($values),
                'value' => ['start' => $start, 'end' => $end],
                // Update writes the window as start_date and end_date, so a date_range: term, which no report reads,
                // is dropped
                'owns' => [$key],
            ]];
        }

        if (! $multiple && (($key == 'as_of') || $this->hasOnlyDateValues($values))) {
            return ['strip', [
                'key' => $key,
                'kind' => 'as_of',
                'label' => $label,
                'presets' => $this->filters['presets'][$name] ?? $this->getFilterBarDatePresets($values),
                'value' => $this->getFilterBarDate($name, $key),
                'owns' => [$key],
            ]];
        }

        if ($key == 'columns') {
            $token = $this->getSearchStringValue($key);

            return ['display', [
                'key' => $key,
                'kind' => 'columns',
                'label' => $label,
                'options' => $this->getFilterBarOptions($values),
                'value' => $this->getFilterBarColumns($key, $values),
                'in_url' => is_string($token) && ($token !== ''),
                'resets' => $this->filters['resets'][$name] ?? [],
                'owns' => [$key],
            ]];
        }

        $section = match (true) {
            in_array($key, ['sort_by', 'direction']) => 'display',
            in_array($key, ['date_field', 'period', 'group']) => 'strip',
            in_array($key, static::FILTER_BAR_ACCOUNTING) || ! is_null($field) => 'strip',
            default => 'filters',
        };

        if ($section == 'filters') {
            return [$section, $this->getFilterBarFilter($name, $key, $values, $label, $multiple)];
        }

        if (empty($values)) {
            return [$section, null];
        }

        $control = [
            'key' => $key,
            'kind' => $this->getFilterBarSettingKind($key, $section, $values),
            'label' => $label,
            'options' => $this->getFilterBarOptions($values),
            'value' => $this->getFilterBarChoice($name, $key, $values, $field),
            'owns' => [$key],
        ];

        // Date Type says which date the window is of
        if ($key == 'date_field') {
            $control['qualifies'] = 'date_range';
        }

        return [$section, $control];
    }

    /**
     * A switch for the accounting settings and two-way display options, a select otherwise: with Inventory or
     * Projects a group gains values, so a count-based rule would turn Group By from a switch into a select.
     */
    protected function getFilterBarSettingKind(string $key, string $section, array $values): string
    {
        return in_array($key, static::FILTER_BAR_ACCOUNTING) || (($section == 'display') && (count($values) == 2))
            ? 'switch'
            : 'select';
    }

    /**
     * A data filter: a checklist when it takes several values, else a list of one choice, both searching the
     * listener's route when it has one. Exclude is offered only where the filter declares that its report honours
     * "not" (operators not_equal true).
     */
    protected function getFilterBarFilter(string $name, string $key, array $values, string $label, bool $multiple): ?array
    {
        $remote = $this->getFilterBarRemote($name);

        if (empty($values) && is_null($remote)) {
            return null;
        }

        $exclude = $multiple && (($this->filters['operators'][$name]['not_equal'] ?? false) === true);

        [$value, $negated, $readable] = $this->getFilterBarSelection($key, $values, $multiple);

        // A term the filter cannot show, such as a "not" it does not declare, stays in the search word for word
        $owned = $readable && (! $negated || $exclude);

        if (! $owned) {
            $value = $multiple ? [] : '';
            $negated = false;
        }

        $ids = $multiple ? $value : array_filter([$value], fn ($id) => $id !== '');
        $missing = array_values(array_diff($ids, array_map('strval', array_keys($values))));

        return [
            'key' => $key,
            'kind' => $multiple ? 'checklist' : 'radio',
            'label' => $label,
            'all' => $this->getFilterBarAllLabel($name),
            'options' => $this->getFilterBarOptions($values),
            'remote' => $remote,
            // The listeners load the first values of a long list, which the remote search goes beyond
            'more' => ! is_null($remote) && (count($values) >= (int) setting('default.select_limit', 10)),
            'exclude' => $exclude,
            'value' => $multiple ? ['ids' => $value, 'not' => $negated] : $value,
            'labels' => (object) array_map('strval', $this->getFilterValueNames($name, $key, $missing)),
            'pill' => ($key == 'sources') ? trans('reports.filter_bar.also_include') : null,
            'resets' => $this->filters['resets'][$name] ?? [],
            'owns' => $owned ? [$key] : [],
        ];
    }

    /**
     * The values of a filter's search string term, whether "not" excludes them, and whether the bar can show the
     * term at all: a range operator gives an array, which no filter control writes.
     */
    protected function getFilterBarSelection(string $key, array $values, bool $multiple): array
    {
        $search = request('search');
        $search = ' ' . (is_string($search) ? $search : '') . ' ';

        foreach ($this->getSpanningValues($values) as $value) {
            if (str_contains($search, ' ' . $key . ':' . $value . ' ')) {
                return [
                    $multiple ? [$value] : $value,
                    str_contains($search, ' not ' . $key . ':' . $value . ' '),
                    true,
                ];
            }
        }

        $value = $this->getSearchStringValue($key);

        if (is_array($value)) {
            return [$multiple ? [] : '', false, false];
        }

        $value = (string) $value;
        $negated = ($value !== '') && ($this->getSearchStringOperator($key) == '!=');

        if (! $multiple) {
            return [$value, $negated, true];
        }

        $ids = array_filter(array_map('trim', explode(',', $value)), fn ($id) => $id !== '');

        return [array_values(array_unique($ids)), $negated, true];
    }

    /**
     * The route a filter searches beyond the values it loaded, and whether the user may read it: the listing
     * answers 403 to a user without its read permission, so the bar keeps to the loaded values then.
     */
    protected function getFilterBarRemote(string $name): ?array
    {
        $route = $this->filters['routes'][$name] ?? null;

        if (empty($route)) {
            return null;
        }

        [$route_name, $parameters] = is_array($route) ? [$route[0], $route[1] ?? []] : [$route, []];

        try {
            $url = route($route_name, $parameters);
        } catch (\Throwable $e) {
            return null;
        }

        return [
            'url' => $url,
            'allowed' => $this->canReadFilterRoute($route_name),
        ];
    }

    /**
     * Whether the user may search a listing route: the read permission assignPermissionsToController() gives its
     * controller. A controller that sets its own, as Common\Contacts does, has no such permission, so its own
     * answer decides, and the bar keeps to the loaded values when that is 403.
     */
    protected function canReadFilterRoute(string $route_name): bool
    {
        $uses = app('router')->getRoutes()->getByName($route_name)?->getAction('uses');

        if (! is_string($uses) || ! str_contains($uses, '\\') || is_null(user())) {
            return true;
        }

        $permission = 'read-' . (new class { use Permissions; })->getControllerPermissionName($uses);

        return user()->can($permission) || ! Permission::where('name', $permission)->exists();
    }

    /**
     * What a filter with nothing chosen reads: "All" with the plural of its name where a translation has one.
     */
    protected function getFilterBarAllLabel(string $name): string
    {
        if (empty($this->filters['names'][$name])) {
            $key = $this->getFilterTranslationKey($name);
            $label = trans($key);

            if (is_string($label) && str_contains($label, '|')) {
                return trans('general.all_type', ['type' => trans_choice($key, 2)]);
            }
        }

        return trans('general.all');
    }

    /**
     * The min_x and max_x chips of one number range, as min key => max key: chips of one value whose values are
     * numbers labelled with themselves. The bar shows them as one field with two decimal text inputs, which take
     * any plain number, as the readers do.
     */
    protected function getFilterBarAmountPairs(array $chips): array
    {
        $names = [];

        foreach (array_keys($chips) as $name) {
            $names[$this->getFilterKey($name)] = $name;
        }

        $pairs = [];

        foreach ($names as $key => $name) {
            if (! preg_match('/^min_(\w+)$/', $key, $parts) || ! isset($names['max_' . $parts[1]])) {
                continue;
            }

            $max = $names['max_' . $parts[1]];

            if ($this->isFilterBarNumberChip($name, $chips[$name]) && $this->isFilterBarNumberChip($max, $chips[$max])) {
                $pairs[$key] = 'max_' . $parts[1];
            }
        }

        return $pairs;
    }

    protected function isFilterBarNumberChip(string $name, array $values): bool
    {
        return ! $this->isMultipleFilter($name)
            && collect($values)->every(fn ($label, $value) => is_numeric($value) && ((string) $label === (string) $value));
    }

    /**
     * A number range's field, named after what it ranges over: amount for min_amount and max_amount.
     */
    protected function getFilterBarAmount(string $min_key, string $max_key): array
    {
        $base = substr($min_key, 4);
        $key = 'general.' . $base;
        $label = trans($key);

        // The readers skip a negated bound, as InvoiceBillList does, so its term stays in the search as it is
        $owned = array_values(array_filter([$min_key, $max_key], fn ($key) => $this->getSearchStringOperator($key) != '!='));

        $value = function (string $key) use ($owned): string {
            $value = $this->getSearchStringValue($key);

            return (in_array($key, $owned, true) && is_numeric($value)) ? (string) $value : '';
        };

        return [
            'key' => $base,
            'kind' => 'amount',
            'label' => ! is_string($label) || ($label == $key) ? Str::headline($base) : (str_contains($label, '|') ? trans_choice($key, 1) : $label),
            'keys' => ['min' => $min_key, 'max' => $max_key],
            'value' => ['min' => $value($min_key), 'max' => $value($max_key)],
            'owns' => $owned,
        ];
    }

    /**
     * The value a setting control shows, as the report reads it: the search string's when it is one of the
     * control's values (even after a "not", which getFieldValue() reads past), else the saved setting of a field of
     * the same name, else the listener's default, else the first value.
     */
    protected function getFilterBarChoice(string $name, string $key, array $values, ?array $field): string
    {
        $value = $this->getSearchStringValue($key);

        if (is_string($value) && array_key_exists($value, $values)) {
            return $value;
        }

        $choices = [
            $field ? $this->getSetting($key, $field['selected'] ?? '') : null,
            $this->filters['defaults'][$name] ?? null,
        ];

        foreach ($choices as $choice) {
            if (is_scalar($choice) && array_key_exists((string) $choice, $values)) {
                return (string) $choice;
            }
        }

        return (string) array_key_first($values);
    }

    /**
     * The window a date range control shows: the one core reads, the request's dates, else the financial year. A
     * report that reads its window elsewhere overrides it.
     */
    protected function getFilterBarDates(): array
    {
        [$start, $end] = $this->getStartAndEndDates($this->year);

        return [$start->toDateString(), $end->toDateString()];
    }

    /**
     * The presets of a date range chip, whose values are start-to-end dates plus custom.
     */
    protected function getFilterBarRangePresets(array $values): array
    {
        $presets = [];

        foreach ($values as $value => $label) {
            if (preg_match('/^(\d{4}-\d{2}-\d{2})-to-(\d{4}-\d{2}-\d{2})$/', (string) $value, $parts)) {
                $presets[] = ['start' => $parts[1], 'end' => $parts[2], 'label' => (string) $label];
            }
        }

        return $presets;
    }

    /**
     * The date a single-date control shows and Print, PDF and Excel state: for As of, the one the report applies
     * (getAsOfDate(), which a report may also read from older terms); else the search string's, parsed as
     * getAsOfDate() parses it, else the listener's default, else today.
     */
    protected function getFilterBarDate(string $name, string $key): string
    {
        if ($key == 'as_of') {
            return $this->getAsOfDate()->toDateString();
        }

        $date = $this->getSearchStringValue($key);

        if (is_string($date) && ($date !== '') && ($parsed = rescue(fn () => Date::parse($date)->toDateString(), null, false))) {
            return $parsed;
        }

        $default = $this->filters['defaults'][$name] ?? null;

        return (is_string($default) && $this->isDateValue($default))
            ? Date::parse($default)->toDateString()
            : Date::today()->toDateString();
    }

    protected function getFilterBarDatePresets(array $values): array
    {
        $presets = [];

        foreach ($values as $date => $label) {
            // AddAsOf adds the applied date labelled with itself, so the old chip could show it: that is no preset
            if (! $this->isDateValue((string) $date) || ($label === company_date($date))) {
                continue;
            }

            $presets[] = ['date' => Date::parse($date)->toDateString(), 'label' => (string) $label];
        }

        return $presets;
    }

    protected function hasOnlyDateValues(array $values): bool
    {
        return ! empty($values) && collect(array_keys($values))->every(fn ($value) => $this->isDateValue((string) $value));
    }

    /**
     * The columns a column picker shows, in their order: the report's resolved columns when it keeps them, as
     * InvoiceBillList does, else the search string's.
     */
    protected function getFilterBarColumns(string $key, array $values): array
    {
        $columns = (isset($this->columns) && is_array($this->columns))
            ? $this->columns
            : explode(',', (string) $this->getSearchStringValue($key));

        return array_values(array_filter(array_map('strval', $columns), fn ($column) => array_key_exists($column, $values)));
    }

    /**
     * Values as ordered [value, label] pairs, so JavaScript keeps their order: an object keyed by ids would be
     * sorted by id.
     */
    protected function getFilterBarOptions(array $values): array
    {
        $options = [];

        foreach ($values as $value => $label) {
            $options[] = [(string) $value, is_scalar($label) ? (string) $label : (string) $value];
        }

        return $options;
    }

    /**
     * A saved setting the bar has no control for, listed as it is saved: Reports → Edit changes it.
     */
    protected function getFilterBarSavedPreference(string $name, array $field, array $values): ?array
    {
        $value = $this->getSetting($name, $field['selected'] ?? '');

        if (! is_scalar($value) || ((string) $value === '')) {
            return null;
        }

        return [
            'label' => $field['title'] ?? $name,
            'value' => $this->getAppliedValueText((string) $value, $values[$value] ?? $value),
        ];
    }

    /**
     * The controls of a section in the bar's order: by the keys it lists, else by kind, so a single date of any key
     * takes the as_of slot; then the others in the order the listeners registered them, the column picker last.
     */
    protected function sortFilterBarControls(string $section, array $controls): array
    {
        $order = array_flip(static::FILTER_BAR_ORDER[$section]);

        // By key, else by kind, so a single date of any key takes the as_of slot
        $rank = fn (array $control) => [
            $order[$control[1]['key']] ?? $order[$control[1]['kind']] ?? (($control[1]['key'] == 'columns') ? PHP_INT_MAX : PHP_INT_MAX - 1),
            $control[0],
        ];

        usort($controls, fn ($a, $b) => $rank($a) <=> $rank($b));

        return array_column($controls, 1);
    }

    /**
     * The applied settings as [label, value] rows, for the line the bar shows on phones and on reports without a
     * date control: the window first, then the strip and the display options, then the saved preferences.
     */
    protected function getFilterBarSummary(array $sections, array $readonly): array
    {
        $controls = collect(array_merge($sections['strip'], $sections['display']));

        $text = fn (array $control) => collect($control['options'] ?? [])->first(fn ($option) => $option[0] === $control['value'])[1] ?? $control['value'];
        $dates = fn (string $start, string $end) => trans('reports.applied_filters.dates', ['start' => company_date($start), 'end' => company_date($end)]);

        $rows = [];

        if ($range = $controls->firstWhere('kind', 'range')) {
            ['start' => $start, 'end' => $end] = $range['value'];

            $preset = collect($range['presets'])->first(fn ($preset) => ($preset['start'] == $start) && ($preset['end'] == $end));
            $qualifier = $controls->firstWhere('qualifies', 'date_range');

            $rows[] = [
                $qualifier ? $text($qualifier) : $range['label'],
                $preset ? trans('reports.applied_filters.named_date', ['name' => $preset['label'], 'date' => $dates($start, $end)]) : $dates($start, $end),
            ];
        } elseif (! $controls->contains('kind', 'as_of')) {
            // No date control: the report covers the financial year, unless a link asked for other dates
            [$start, $end] = $this->getStartAndEndDates($this->year);

            $rows[] = [
                (request()->filled('start_date') && request()->filled('end_date')) ? trans('reports.date_range') : trans('general.financial_year'),
                $dates($start->toDateString(), $end->toDateString()),
            ];
        }

        foreach ($controls as $control) {
            if ($control['kind'] == 'as_of') {
                $preset = collect($control['presets'])->firstWhere('date', $control['value']);

                $rows[] = [$control['label'], $this->getAppliedValueText($control['value'], $preset['label'] ?? $control['value'])];
            } elseif (in_array($control['kind'], ['select', 'switch']) && empty($control['qualifies'])) {
                $rows[] = [$control['label'], $text($control)];
            }
        }

        foreach ($readonly as $row) {
            $rows[] = [$row['label'], $row['value']];
        }

        return $rows;
    }

    /**
     * The bar's own words, each key falling back to the default locale's, as a locale may translate only some.
     */
    protected function getFilterBarTexts(): array
    {
        $fallback = trans('reports.filter_bar', [], config('app.fallback_locale'));
        $texts = trans('reports.filter_bar');

        return array_merge(is_array($fallback) ? $fallback : [], is_array($texts) ? $texts : [], [
            'customise' => trans('general.customize'),
            'custom' => trans('general.date_range.custom'),
            'no_matching_data' => trans('general.no_matching_data'),
        ]);
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

    /**
     * The end of the day a report of balances is drawn up as of: the search string's as_of, a date or a word such
     * as yesterday, else today. App\Listeners\Report\AddAsOf offers it on the report options bar.
     */
    public function getAsOfDate(): Date
    {
        $value = $this->getSearchStringValue('as_of');

        $date = (is_string($value) && ($value !== ''))
            ? rescue(fn () => Date::parse(Date::parse($value)->toDateString()), null, false)
            : null;

        return ($date ?? Date::today())->endOfDay();
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

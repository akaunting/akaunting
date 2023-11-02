<?php

namespace App\Traits;

use App\Utilities\Date;
use Carbon\CarbonPeriod;

trait DateTime
{
    public function getCompanyDateFormat(): string
    {
        $default = 'd M Y';

        // Make sure it's installed
        if (! config('app.installed') && ! env_is_testing()) {
            return $default;
        }

        // Make user is logged in
        if (!user()) {
            return $default;
        }

        $chars = ['dash' => '-', 'slash' => '/', 'dot' => '.', 'comma' => ',', 'space' => ' '];

        $date_format = (setting('localisation.date_format', $default)) ?? $default;

        $char = (setting('localisation.date_separator', 'space')) ?? 'space';
        $date_separator = $chars[$char];

        return str_replace(' ', $date_separator, $date_format);
    }

    public function scopeDateFilter($query, string $field)
    {
        [$start, $end] = $this->getStartAndEndDates();

        return $query->whereBetween($field, [$start->toDateTimeString(), $end->toDateTimeString()]);
    }

    public function getStartAndEndDates($year = null): array
    {
        if (request()->filled('start_date') && request()->filled('end_date')) {
            $start = Date::parse(request('start_date'))->startOfDay();
            $end = Date::parse(request('end_date'))->endOfDay();
        } else {
            $financial_year = $this->getFinancialYear($year);

            $start = $financial_year->copy()->getStartDate();
            $end = $financial_year->copy()->getEndDate();
        }

        return [$start, $end];
    }

    public function getFinancialStart($year = null): Date
    {
        $start_of_year = Date::now()->startOfYear();
        $start_date = request()->filled('start_date') ? Date::parse(request('start_date')) : null;

        $setting = explode('-', setting('localisation.financial_start'));

        $day = ! empty($setting[0]) ? $setting[0] : (! empty($start_date) ? $start_date->day : $start_of_year->day);
        $month = ! empty($setting[1]) ? $setting[1] : (! empty($start_date) ? $start_date->month : $start_of_year->month);
        $year = $year ?? (! empty($start_date) ? $start_date->year : $start_of_year->year);

        $financial_start = Date::create($year, $month, $day);

        if ((setting('localisation.financial_denote') == 'ends') && ($financial_start->dayOfYear != 1)) {
            $financial_start->subYear();
        }

        return $financial_start;
    }

    public function getFinancialWeek($year = null): CarbonPeriod
    {
        $today = Date::today();
        $financial_weeks = $this->getFinancialWeeks($year);

        foreach ($financial_weeks as $week) {
            if ($today->lessThan($week->getStartDate()) || $today->greaterThan($week->getEndDate())) {
                continue;
            }

            $this_week = $week;

            break;
        }

        if (! isset($this_week)) {
            $this_week = $financial_weeks[0];
        }

        return $this_week;
    }

    public function getFinancialMonth($year = null): CarbonPeriod
    {
        $today = Date::today();
        $financial_months = $this->getFinancialMonths($year);

        foreach ($financial_months as $month) {
            if ($today->lessThan($month->getStartDate()) || $today->greaterThan($month->getEndDate())) {
                continue;
            }

            $this_month = $month;

            break;
        }

        if (! isset($this_month)) {
            $this_month = $financial_months[0];
        }

        return $this_month;
    }

    public function getFinancialQuarter($year = null): CarbonPeriod
    {
        $today = Date::today();
        $financial_quarters = $this->getFinancialQuarters($year);

        foreach ($financial_quarters as $quarter) {
            if ($today->lessThan($quarter->getStartDate()) || $today->greaterThan($quarter->getEndDate())) {
                continue;
            }

            $this_quarter = $quarter;

            break;
        }

        if (! isset($this_quarter)) {
            $this_quarter = $financial_quarters[0];
        }

        return $this_quarter;
    }

    public function getFinancialYear($year = null): CarbonPeriod
    {
        $financial_start = $this->getFinancialStart($year);

        return CarbonPeriod::create($financial_start, $financial_start->copy()->addYear()->subDay()->endOfDay());
    }

    public function getFinancialWeeks($year = null): array
    {
        $weeks = [];

        $start = $this->getFinancialStart($year);

        for ($i = 0; $i < 52; $i++) {
            $weeks[] = CarbonPeriod::create($start->copy()->addWeeks($i), $start->copy()->addWeeks($i + 1)->subDay()->endOfDay());
        }

        return $weeks;
    }

    public function getFinancialMonths($year = null): array
    {
        $months = [];

        $start = $this->getFinancialStart($year);

        for ($i = 0; $i < 12; $i++) {
            $months[] = CarbonPeriod::create($start->copy()->addMonths($i), $start->copy()->addMonths($i + 1)->subDay()->endOfDay());
        }

        return $months;
    }

    public function getFinancialQuarters($year = null): array
    {
        $quarters = [];

        $start = $this->getFinancialStart($year);

        for ($i = 0; $i < 4; $i++) {
            $quarters[] = CarbonPeriod::create($start->copy()->addQuarters($i), $start->copy()->addQuarters($i + 1)->subDay()->endOfDay());
        }

        return $quarters;
    }

    public function getDatePickerShortcuts(): array
    {
        $today = Date::today();
        $financial_week = $this->getFinancialWeek();
        $financial_month = $this->getFinancialMonth();
        $financial_quarter = $this->getFinancialQuarter();
        $financial_year = $this->getFinancialYear();

        $date_picker_shortcuts = [
            trans('general.date_range.this_week') => [
                'start' => $financial_week->copy()->getStartDate()->toDateString(),
                'end' => $financial_week->copy()->getEndDate()->toDateString(),
            ],
            trans('general.date_range.this_month') => [
                'start' => $financial_month->copy()->getStartDate()->toDateString(),
                'end' => $financial_month->copy()->getEndDate()->toDateString(),
            ],
            trans('general.date_range.this_quarter') => [
                'start' => $financial_quarter->copy()->getStartDate()->toDateString(),
                'end' => $financial_quarter->copy()->getEndDate()->toDateString(),
            ],
            trans('general.date_range.this_year') => [
                'start' => $financial_year->copy()->getStartDate()->toDateString(),
                'end' => $financial_year->copy()->getEndDate()->toDateString(),
            ],
            trans('general.date_range.year_to_date') => [
                'start' => $financial_year->copy()->getStartDate()->toDateString(),
                'end' => $today->copy()->toDateString(),
            ],
            trans('general.date_range.previous_week') => [
                'start' => $financial_week->copy()->getStartDate()->subWeek()->toDateString(),
                'end' => $financial_week->copy()->getEndDate()->subWeek()->toDateString(),
            ],
            trans('general.date_range.previous_month') => [
                'start' => $financial_month->copy()->getStartDate()->subMonth()->toDateString(),
                'end' => $financial_month->copy()->getEndDate()->subMonth()->toDateString(),
            ],
            trans('general.date_range.previous_quarter') => [
                'start' => $financial_quarter->copy()->getStartDate()->subQuarter()->toDateString(),
                'end' => $financial_quarter->copy()->getEndDate()->subQuarter()->toDateString(),
            ],
            trans('general.date_range.previous_year') => [
                'start' => $financial_year->copy()->getStartDate()->subYear()->toDateString(),
                'end' => $financial_year->copy()->getEndDate()->subYear()->toDateString(),
            ],
        ];

        return $date_picker_shortcuts;
    }

    public function getDailyDateFormat(): string
    {
        return 'd M Y';
    }

    public function getWeeklyDateFormat(): string
    {
        return 'W - M Y';
    }

    public function getMonthlyDateFormat(): string
    {
        return 'M Y';
    }

    public function getQuarterlyDateFormat(): string
    {
        return 'M Y';
    }

    public function getYearlyDateFormat(): string
    {
        return 'Y';
    }

    public function getTimezones(): array
    {
        // The list of available timezone groups to use.
        $use_zones = ['Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific'];

        // Get the list of time zones from the server.
        $zones = \DateTimeZone::listIdentifiers();

        return collect($zones)
                ->mapWithKeys(function ($timezone) use ($use_zones) {
                    if (! in_array(str($timezone)->before('/'), $use_zones)) {
                        return [];
                    }

                    return [$timezone => str($timezone)->after('/')->value()];
                })
                ->groupBy(function ($item, $key) {
                    return str($key)->before('/')->value();
                }, preserveKeys: true)
                ->toArray();
    }

    // @deprecated 3.1
    public function scopeMonthsOfYear($query, string $field)
    {
        return $this->scopeDateFilter($query, $field);
    }
}

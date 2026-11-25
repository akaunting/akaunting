<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;
use App\Traits\DateTime;
use App\Utilities\Date;

class Localisation extends SettingController
{
    use DateTime;

    public $prefix = 'localisation';

    public function edit()
    {
        $timezones = $this->getTimezones();

        // Each format is shown with a sample date, like 31 Dec 2017
        $date_formats = collect(config('date.formats'))->mapWithKeys(function ($format) {
            return [$format => Date::parse('2017-12-31')->format($format)];
        })->all();

        $date_separators = collect(config('date.separators'))->mapWithKeys(function ($character, $separator) {
            return [$separator => trans('settings.localisation.date.' . $separator)];
        })->all();

        $percent_positions = [
            'before' => trans('settings.localisation.percent.before'),
            'after' => trans('settings.localisation.percent.after'),
        ];

        $discount_locations = [
            'no' => trans('general.disabled'),
            'item' => trans('settings.localisation.discount_location.item'),
            'total' => trans('settings.localisation.discount_location.total'),
            'both' => trans('settings.localisation.discount_location.both'),
        ];

        $financial_denote_options = [
            'begins' => trans('settings.localisation.financial_denote.begins'),
            'ends' => trans('settings.localisation.financial_denote.ends'),
        ];

        return view('settings.localisation.edit', compact(
            'timezones',
            'date_formats',
            'date_separators',
            'percent_positions',
            'discount_locations',
            'financial_denote_options'
        ));
    }
}

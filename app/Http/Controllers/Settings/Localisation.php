<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Setting;
use App\Traits\DateTime;

class Localisation extends Controller
{
    use DateTime;

    public function edit()
    {
        $setting = Setting::prefix('localisation')->get()->transform(function ($s) {
            $s->key = str_replace('localisation.', '', $s->key);

            return $s;
        })->pluck('value', 'key');

        $timezones = $this->getTimezones();

        $date_formats = [
            'd M Y' => '31 Dec 2017',
            'd F Y' => '31 December 2017',
            'd m Y' => '31 12 2017',
            'm d Y' => '12 31 2017',
            'Y m d' => '2017 12 31',
        ];

        $date_separators = [
            'dash' => trans('settings.localisation.date.dash'),
            'slash' => trans('settings.localisation.date.slash'),
            'dot' => trans('settings.localisation.date.dot'),
            'comma' => trans('settings.localisation.date.comma'),
            'space' => trans('settings.localisation.date.space'),
        ];

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

        return view('settings.localisation.edit', compact(
            'setting',
            'timezones',
            'date_formats',
            'date_separators',
            'percent_positions',
            'discount_locations'
        ));
    }
}

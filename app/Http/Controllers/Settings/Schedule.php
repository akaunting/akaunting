<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Setting;

class Schedule extends Controller
{
    public function edit()
    {
        $setting = Setting::prefix('schedule')->get()->transform(function ($s) {
            $s->key = str_replace('schedule.', '', $s->key);

            return $s;
        })->pluck('value', 'key');

        return view('settings.schedule.edit', compact(
            'setting'
        ));
    }
}

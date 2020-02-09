<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Setting;

class Company extends Controller
{
    public function edit()
    {
        $setting = Setting::prefix('company')->get()->transform(function ($s) {
            $s->key = str_replace('company.', '', $s->key);

            return $s;
        })->pluck('value', 'key');

        return view('settings.company.edit', compact(
            'setting'
        ));
    }
}

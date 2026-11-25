<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;

class Schedule extends SettingController
{
    public $prefix = 'schedule';

    public function edit()
    {
        return view('settings.schedule.edit');
    }
}

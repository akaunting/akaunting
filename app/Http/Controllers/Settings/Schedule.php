<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;

class Schedule extends SettingController
{
    public function edit()
    {
        return view('settings.schedule.edit');
    }
}

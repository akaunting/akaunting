<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;

class Company extends SettingController
{
    public function edit()
    {
        return view('settings.company.edit');
    }
}

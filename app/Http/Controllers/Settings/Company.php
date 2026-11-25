<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;

class Company extends SettingController
{
    public $prefix = 'company';

    public function edit()
    {
        return view('settings.company.edit');
    }
}

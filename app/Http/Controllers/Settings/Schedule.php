<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;

class Schedule extends Controller
{
    public function edit()
    {
        return view('settings.schedule.edit');
    }
}

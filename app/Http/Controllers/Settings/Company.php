<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;

class Company extends Controller
{
    public function edit()
    {
        return view('settings.company.edit');
    }
}

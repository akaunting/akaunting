<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;

class Bill extends Controller
{
    public function edit()
    {
        return view('settings.bill.edit');
    }
}

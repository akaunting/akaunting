<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Currency;

class Currencies extends Widget
{
    public $default_name = 'widgets.currencies';

    public function show()
    {
        $currencies = Currency::enabled()->take(5)->get();

        return $this->view('widgets.currencies', [
            'currencies' => $currencies,
        ]);
    }

}
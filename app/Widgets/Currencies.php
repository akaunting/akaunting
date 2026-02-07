<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Setting\Currency;

class Currencies extends Widget
{
    public $default_name = 'widgets.currencies';

    public function show()
    {
        $this->setData();

        return $this->view('widgets.currencies', $this->data);
    }

    public function setData(): void
    {
        $currencies = Currency::enabled()->take(5)->get();

        $this->data = [
            'currencies' => $currencies,
        ];
    }
}

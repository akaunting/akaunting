<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;
use App\Models\Setting\Currency;

class Totals extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');
        $currency = Currency::where('code', setting('default.currency'))->first();

        return view('components.documents.form.totals', compact('currencies', 'currency'));
    }
}

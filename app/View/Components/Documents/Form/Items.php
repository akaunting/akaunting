<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;
use App\Models\Setting\Tax;

class Items extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $taxes = Tax::enabled()->orderBy('name')->get();

        return view('components.documents.form.items', compact('taxes'));
    }
}

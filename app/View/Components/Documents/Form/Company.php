<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;
use Illuminate\Support\Str;

class Company extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $company = user()->companies()->first();

        $inputNameType = config('type.' . $this->type . '.route.parameter');

        return view('components.documents.form.company', compact('company','inputNameType'));
    }
}

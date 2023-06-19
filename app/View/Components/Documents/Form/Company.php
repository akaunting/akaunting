<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\Documents\Form as Component;

class Company extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $company = company();

        $inputNameType = config('type.document.' . $this->type . '.route.parameter');

        return view('components.documents.form.company', compact('company', 'inputNameType'));
    }
}

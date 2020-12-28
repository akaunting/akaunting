<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;

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

        $inputNameType = Str::replaceFirst('-', '_', $this->type);

        return view('components.documents.form.company', compact('company','inputNameType'));
    }
}

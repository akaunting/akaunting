<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;
use App\Models\Common\Company as Model;

class Company extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $company = Model::find(company_id());

        $inputNameType = config('type.' . $this->type . '.route.parameter');

        return view('components.documents.form.company', compact('company','inputNameType'));
    }
}

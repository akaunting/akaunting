<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;

class Content extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $status = 'draft';

        if (!empty($this->document)) {
            $status = $this->document->status;
        }

        return view('components.documents.form.content', compact('status'));
    }
}

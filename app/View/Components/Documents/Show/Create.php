<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;

class Create extends Component
{
    public $description;

    public $created_date;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = ($this->document->isRecurringDocument()) ? 'invoices.slider.create_recurring' : 'invoices.slider.create';
        $this->created_date = '<span class="font-medium">' . company_date($this->document->created_at) . '</span>';

        return view('components.documents.show.create');
    }
}

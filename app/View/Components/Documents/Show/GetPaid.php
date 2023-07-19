<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;

class GetPaid extends Component
{
    public $description;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = trans('general.amount_due') . ': ' . '<span class="font-medium">' . money($this->document->amount_due, $this->document->currency_code) . '</span>';

        return view('components.documents.show.get-paid');
    }
}

<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;

class Restore extends Component
{
    public $description;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $last_history = $this->document->histories()->orderBy('id', 'desc')->first();

        $this->description = trans('invoices.cancel_date') . ': ';
        $this->description .= '<span class="font-medium">' . company_date($last_history->created_at) . '</span>';

        return view('components.documents.show.restore');
    }
}

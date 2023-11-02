<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;
use Illuminate\Support\Str;

class Restore extends Component
{
    public $description;

    public $user_name;

    public $type_lowercase;

    public $last_cancelled;

    public $last_cancelled_date;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = 'documents.slider.cancel';

        $this->last_cancelled = $this->document->histories()->status('cancelled')->latest()->first();

        $this->user_name = ($this->last_cancelled) ? $this->last_cancelled->owner->name : trans('general.na');

        $this->type_lowercase = Str::lower(trans_choice($this->textPage, 1));

        $this->last_cancelled_date = '<span class="font-medium">' . company_date($this->last_cancelled->created_at) . '</span>';

        return view('components.documents.show.restore');
    }
}

<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;
use Illuminate\Support\Str;

class Receive extends Component
{
    public $description;

    public $user_name;

    public $type_lowercase;

    public $last_received;

    public $last_received_date;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = $this->document->isRecurringDocument() ? 'documents.slider.create_recurring' : 'general.last_received';

        $this->last_received = $this->document->histories()->status('received')->latest()->first();

        $this->user_name = ($this->last_received) ? $this->last_received->owner->name : trans('general.na');

        $this->type_lowercase = Str::lower(trans_choice($this->textPage, 1));

        $date = ($this->last_received) ? company_date($this->last_received->created_at) : trans('general.na');

        $this->last_received_date = '<span class="font-medium">' . $date . '</span>';

        return view('components.documents.show.receive');
    }
}

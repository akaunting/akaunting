<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;
use Illuminate\Support\Str;

class Send extends Component
{
    public $description;

    public $user_name;

    public $type_lowercase;

    public $last_sent;

    public $last_sent_date;

    public $histories;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = $this->document->isRecurringDocument() ? 'documents.slider.create_recurring' : 'general.last_sent';

        $this->histories = $this->document->histories()->status('sent')->latest()->get();

        $this->last_sent = $this->histories->first();

        $this->user_name = ($this->last_sent) ? $this->last_sent->owner->name : trans('general.na');

        $this->type_lowercase = Str::lower(trans_choice($this->textPage, 1));

        $date = ($this->last_sent) ? company_date($this->last_sent->created_at) : trans('general.na');

        $this->last_sent_date = '<span class="font-medium">' . $date . '</span>';

        return view('components.documents.show.send');
    }
}

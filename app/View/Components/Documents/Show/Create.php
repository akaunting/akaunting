<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public $description;

    public $user_name;

    public $type_lowercase;

    public $created_date;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = ($this->document->isRecurringDocument()) ? 'documents.slider.create_recurring' : 'documents.slider.create';

        $this->user_name = $this->document->owner->name;

        $this->type_lowercase = Str::lower(trans_choice($this->textPage, 1));

        $this->created_date = '<span class="font-medium">' . company_date($this->document->created_at) . '</span>';

        return view('components.documents.show.create');
    }
}

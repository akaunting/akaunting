<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;
use App\Models\Common\Recurring;
use Illuminate\Support\Str;

class Children extends Component
{
    public $type_lowercase;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $recurring = Recurring::where('recurable_type', 'App\\Models\\Document\\Document')
            ->where('recurable_id', $this->document->id)
            ->first();

        $this->type_lowercase = Str::lower(trans_choice($this->textPage, 2));

        return view('components.documents.show.children', compact('recurring'));
    }
}

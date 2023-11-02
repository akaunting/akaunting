<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;
use App\Models\Common\Recurring;
use Illuminate\Support\Str;

class Schedule extends Component
{
    public $description;

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

        $started_date = '<span class="font-medium">' . company_date($recurring->started_at) . '</span>';
        $frequency = Str::lower(trans('recurring.' . str_replace(['daily', 'ly'], ['days', 's'], $recurring->frequency)));
        $invertal = $recurring->interval;

        $this->description = trans('transactions.slider.schedule', [
            'frequency' => $frequency,
            'interval' => $invertal,
            'date' => $started_date
        ]);

        return view('components.documents.show.schedule', compact('recurring'));
    }
}

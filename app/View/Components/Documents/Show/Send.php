<?php

namespace App\View\Components\Documents\Show;

use App\Models\Document\DocumentHistory;
use App\Abstracts\View\Components\Documents\Show as Component;

class Send extends Component
{
    public $description;

    public $sent_date;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = ($this->document->isRecurringDocument()) ? 'invoices.slider.create_recurring' : 'general.last_sent';

        $last_sent = DocumentHistory::where('document_id', $this->document->id)->whereIn('status', ['sent', 'received'])->latest()->first();

        $date = ($last_sent) ? company_date($last_sent->created_at) : trans('general.na');

        $this->sent_date = '<span class="font-medium">' . $date . '</span>';

        return view('components.documents.show.send');
    }
}

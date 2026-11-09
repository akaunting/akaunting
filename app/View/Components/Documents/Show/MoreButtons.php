<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;
use App\Models\Document\Document;

class MoreButtons extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        // Only a document in the books can be cancelled
        if (in_array($this->document->status, Document::NOT_ACCRUED_STATUSES)) {
            $this->hideCancel = true;
        }

        return view('components.documents.show.more-buttons');
    }
}

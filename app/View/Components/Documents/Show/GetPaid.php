<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;

class GetPaid extends Component
{
    public $description;

    public $text_document_transaction = 'documents.transaction';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->description = trans('general.amount_due') . ': ' . '<span class="font-medium">' . money($this->document->amount_due, $this->document->currency_code) . '</span>';

        if (
            request()->isSigned($this->document->company_id)
            || request()->isPreview($this->document->company_id)
            || request()->isPortal($this->document->company_id)
        ) {
            $this->text_document_transaction = 'documents.portal_transaction';
        }

        return view('components.documents.show.get-paid');
    }
}

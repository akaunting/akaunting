<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Components\Documents\Show as Component;

class Attachment extends Component
{
    public $transaction_attachment;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->transaction_attachment = collect();

        if ($this->document->transactions->count()) {
            foreach ($this->document->transactions as $transaction) {
                if (! $transaction->attachment) {
                    continue;
                }

                foreach ($transaction->attachment as $file) {
                    $this->transaction_attachment->push($file);
                }
            }
        }

        return view('components.documents.show.attachment');
    }
}

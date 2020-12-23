<?php

namespace App\Abstracts\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

abstract class DocumentForm extends Component
{
    public $type;

    public $documents;

    public $bulkActions;

    /** @var bool */
    public $hideBulkAction;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $documents = [], $bulkActions = [],
        bool $hideBulkAction = false
    ) {
        $this->type = $type;
        $this->documents = $documents;

        $this->bulkActions = $bulkActions;
        $this->hideBulkAction = $hideBulkAction;
    }
}

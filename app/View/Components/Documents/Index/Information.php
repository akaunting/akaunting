<?php

namespace App\View\Components\Documents\Index;

use App\Abstracts\View\Component;
use App\Traits\ViewComponents;

class Information extends Component
{
    use ViewComponents;

    public const OBJECT_TYPE = 'contact';
    public const DEFAULT_TYPE = 'customer';
    public const DEFAULT_PLURAL_TYPE = 'customers';

    public $id;

    public $document;

    public $hideShow;

    public $showRoute;

    public $showDocumentRoute;

    public $placement;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $document, bool $hideShow = false, string $showRoute = '', string $showDocumentRoute = '', string $placement = '', string $id = ''
    ) {
        $this->document = $document;
        $this->hideShow = $hideShow;
        $this->showRoute = $this->getShowRoute($document->contact->type, $showRoute);
        $this->showDocumentRoute = $this->getShowRoute($document->type, $showDocumentRoute);
        $this->placement = (! empty($placement)) ? $placement : 'left';
        $this->id = (! empty($id)) ? $id : 'tooltip-information-' . $document->id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.index.information');
    }
}

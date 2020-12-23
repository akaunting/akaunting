<?php

namespace App\View\Components\Documents\Form;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Content extends Component
{
    /** @var string */
    public $type;

    public $document;

    /** @var string */
    public $formRoute;

    /** @var string */
    public $formId;

    /** @var string */
    public $formSubmit;

    /** @var bool */
    public $hideCompany;

    /** @var bool */
    public $hideAdvanced;

    /** @var bool */
    public $hideFooter;

    /** @var bool */
    public $hideButtons;

    /* Company Component Field Status */
    /** @var bool */
    public $hideLogo;

    /** @var bool */
    public $hideDocumentTitle;

    /** @var bool */
    public $hideDocumentSubheading;

    /** @var bool */
    public $hideCompanyEdit;
    /* Company Component Field Status */

    /* Main.Header Fields */
    /** @var string */
    public $contactType;

    /** @var string */
    public $documentNumber;

    /** @var string */
    public $textIssuedAt;

    /** @var string */
    public $textDueAt;

    /** @var string */
    public $textDocumentNumber;

    /** @var string */
    public $textOrderNumber;
    /* Main.Header Fields */

    /* Main.Header Component Field Status */
    /** @var bool */
    public $hideContact;

    /** @var bool */
    public $hideIssuedAt;

    /** @var bool */
    public $hideDocumentNumber;

    /** @var bool */
    public $hideDueAt;

    /** @var bool */
    public $hideOrderNumber;
    /* Main.Header Component Field Status */

    /* Advanced Component Field Status */
    /** @var bool */
    public $hideRecurring;

    /** @var bool */
    public $hideCategory;

    /** @var bool */
    public $hideAttachment;
    /* Advanced Component Field Status */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, $document = false, string $formRoute = '', string $formId = 'document', string $formSubmit = 'onSubmit', bool $hideCompany = false, bool $hideAdvanced = false, bool $hideFooter = false, bool $hideButtons = false,
        bool $hideLogo = false, bool $hideDocumentTitle = false, bool $hideDocumentSubheading = false, bool $hideCompanyEdit = false, // Company
        string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '', // Main.Header
        string $contactType = '', string $documentNumber = '',
        bool $hideContact = false, bool $hideIssuedAt = false, bool $hideDocumentNumber = false, bool $hideDueAt = false, bool $hideOrderNumber = false, // Main.Header
        bool $hideRecurring = false, bool $hideCategory = false, bool $hideAttachment = false // Advanced
    )
    {
        $this->type = $type;
        $this->document = $document;
        $this->formRoute = ($formRoute) ? $formRoute : $this->getRoute($type, $document);
        $this->formId = $formId;
        $this->formSubmit = $formSubmit;

        $this->hideCompany = $hideCompany;
        $this->hideAdvanced = $hideAdvanced;
        $this->hideFooter = $hideFooter;
        $this->hideButtons = $hideButtons;

        // Company component fields status
        $this->hideLogo = $hideLogo;
        $this->hideDocumentTitle = $hideDocumentTitle;
        $this->hideDocumentSubheading = $hideDocumentSubheading;
        $this->hideCompanyEdit = $hideCompanyEdit;

        // Main.Header fields
        $this->contactType = $contactType;
        $this->documentNumber = $documentNumber;

        $this->textIssuedAt = $textIssuedAt;
        $this->textDocumentNumber = $textDocumentNumber;
        $this->textDueAt = $textDueAt;
        $this->textOrderNumber = $textOrderNumber;

        // Main.Header component fields status
        $this->hideContact = $hideContact;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->hideDueAt = $hideDueAt;
        $this->hideOrderNumber = $hideOrderNumber;

        // Advanced component fields status
        $this->hideRecurring = $hideRecurring;
        $this->hideCategory = $hideCategory;
        $this->hideAttachment = $hideAttachment;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.form.content');
    }

    protected function getRoute($type, $document, $parameters = [])
    {
        $page = Str::plural($type, 2);

        $route = $page . '.store';

        if ($document) {
            $parameters = [
                Str::replaceFirst('-', '_', $type) => $document->id
            ];

            $route = $page . '.update';
        }

        try {
            route($route, $parameters);
        } catch (\Exception $e) {
            $route = '';
        }

        return $route;
    }
}

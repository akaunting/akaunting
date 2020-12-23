<?php

namespace App\View\Components\Documents\Form;

use Illuminate\View\Component;

class Main extends Component
{
    /** @var string */
    public $type;

    public $document;

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

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $type, $document = false,
        string $textDocumentNumber = '', string $textOrderNumber = '', string $textIssuedAt = '', string $textDueAt = '', // Main.Header
        string $contactType = '', string $documentNumber = '',
        bool $hideContact = false, bool $hideIssuedAt = false, bool $hideDocumentNumber = false, bool $hideDueAt = false, bool $hideOrderNumber = false // Main.Header
    ) {
        $this->type = $type;
        $this->document = $document;

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
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.form.main');
    }
}

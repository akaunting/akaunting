<?php

namespace App\View\Components\Documents\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Company extends Component
{
    /** @var string */
    public $type;

    /** @var bool */
    public $hideLogo;

    /** @var bool */
    public $hideDocumentTitle;

    /** @var bool */
    public $hideDocumentSubheading;

    /** @var bool */
    public $hideCompanyEdit;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, bool $hideLogo = false, bool $hideDocumentTitle = false, bool $hideDocumentSubheading = false, bool $hideCompanyEdit = false
    ) {
        $this->type = $type;
        $this->hideLogo = $hideLogo;
        $this->hideDocumentTitle = $hideDocumentTitle;
        $this->hideDocumentSubheading = $hideDocumentSubheading;
        $this->hideCompanyEdit = $hideCompanyEdit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $company = user()->companies()->first();

        $inputNameType = Str::replaceFirst('-', '_', $this->type);

        return view('components.documents.form.company', compact('company','inputNameType'));
    }

    protected function getFilterName($column)
    {
        if (strpos($column, '_id') !== false) {
            $column = str_replace('_id', '', $column);
        } else if (strpos($column, '_code') !== false) {
            $column = str_replace('_code', '', $column);
        }

        $plural = Str::plural($column, 2);

        if (trans_choice('general.' . $plural, 1) !== 'general.' . $plural) {
            return trans_choice('general.' . $plural, 1);
        } elseif (trans_choice('search_string.columns.' . $plural, 1) !== 'search_string.columns.' . $plural) {
            return trans_choice('search_string.columns.' . $plural, 1);
        }

        $name = trans('general.' . $column);

        if ($name == 'general.' . $column) {
            $name = trans('search_string.columns.' . $column);
        }

        return $name;
    }
}

<?php

namespace App\View\Components\Contacts;

use App\Abstracts\View\Component;
use App\Models\Setting\Currency;
use App\Traits\ViewComponents;

class Script extends Component
{
    use ViewComponents;

    public const OBJECT_TYPE = 'contact';
    public const DEFAULT_TYPE = 'customer';
    public const DEFAULT_PLURAL_TYPE = 'customers';

    /** @var string */
    public $type;

    public $contact;

    public $contact_persons;

    public $currencies;

    public $currency_code;

    /** @var string */
    public $alias;

    /** @var string */
    public $folder;

    /** @var string */
    public $file;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type = '', $contact = false, $currencies = [],
        string $alias = '', string $folder = '', string $file = ''
    ) {
        $this->type = $type;
        $this->contact = $contact;
        $this->contact_persons = ($contact) ? $contact->contact_persons : [];
        $this->currencies = $this->getCurrencies($currencies);
        $this->currency_code = ($contact) ? $contact->currency_code : default_currency();

        $this->alias = $this->getAlias($type, $alias);
        $this->folder = $this->getScriptFolder($type, $folder);
        $this->file = $this->getScriptFile($type, $file);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.contacts.script');
    }

    protected function getCurrencies($currencies)
    {
        if (!empty($currencies)) {
            return $currencies;
        }

        return Currency::enabled()->orderBy('name')->get()->makeHidden(['id', 'company_id', 'created_at', 'updated_at', 'deleted_at']);
    }
}

<?php

namespace App\View\Components\Documents;

use App\Models\Setting\Currency;
use App\Models\Setting\Tax;

use Illuminate\View\Component;

class Script extends Component
{
    /** @var string */
    public $type;

    /** @var string */
    public $scriptFile;

    /** @var string */
    public $version;

    public $document;

    public $items;

    public $currencies;

    public $currency_code;

    public $taxes;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $type = '', string $scriptFile = '', string $version = '', $document = false, $items = [], $currencies = [], $taxes = [])
    {
        $this->type = $type;
        $this->scriptFile = ($scriptFile) ? $scriptFile : 'public/js/common/documents.js';
        $this->version = $this->getVersion($version);
        $this->document = $document;
        $this->items = $items;
        $this->currencies = $this->getCurrencies($currencies);
        $this->currency_code = ($document) ? $document->currency_code : setting('default.currency');
        $this->taxes = $this->getTaxes($taxes);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.script');
    }

    protected function getVersion($version)
    {
        if (!empty($version)) {
            return $version;
        }

        if ($alias = config('type.' . $this->type . '.alias')) {
            return module_version($alias);
        }

        return version('short');
    }

    protected function getCurrencies($currencies)
    {
        if (!empty($currencies)) {
            return $currencies;
        }

        return Currency::enabled()->orderBy('name')->get()->makeHidden(['id', 'company_id', 'created_at', 'updated_at', 'deleted_at']);
    }

    protected function getTaxes($taxes)
    {
        if (!empty($taxes)) {
            return $taxes;
        }

        return Tax::enabled()->orderBy('name')->get()->makeHidden(['company_id', 'created_at', 'updated_at', 'deleted_at']);
    }
}

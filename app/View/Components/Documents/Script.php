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

    public $items;

    public $currencies;

    public $taxes;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $type = '', string $scriptFile = '', $items = [], $currencies = [], $taxes = [])
    {
        $this->type = $type;
        $this->scriptFile = ($scriptFile) ? $scriptFile : 'public/js/common/documents.js';
        $this->items = $items;
        $this->currencies = $this->getCurrencies($currencies);
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

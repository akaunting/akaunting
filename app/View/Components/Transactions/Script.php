<?php

namespace App\View\Components\Transactions;

use Illuminate\View\Component;

class Script extends Component
{
    /** @var string */
    public $type;

    /** @var string */
    public $scriptFile;

    /** @var string */
    public $version;

    public $transaction;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $type = '', string $scriptFile = '', string $version = '', $transaction = false)
    {
        $this->type = $type;
        $this->scriptFile = ($scriptFile) ? $scriptFile : 'public/js/banking/transactions.js';
        $this->version = $this->getVersion($version);
        $this->transaction = $transaction;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.transactions.script');
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
}

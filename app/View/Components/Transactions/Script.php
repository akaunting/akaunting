<?php

namespace App\View\Components\Transactions;

use App\Abstracts\View\Component;
use App\Traits\ViewComponents;

class Script extends Component
{
    use ViewComponents;

    public const OBJECT_TYPE = 'transaction';
    public const DEFAULT_TYPE = 'income';
    public const DEFAULT_PLURAL_TYPE = 'incomes';

    /** @var string */
    public $type;

    public $transaction;

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
        string $type = '', $transaction = false,
        string $alias = '', string $folder = '', string $file = ''
    ) {
        $this->type = $type;
        $this->transaction = $transaction;

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
        return view('components.transactions.script');
    }
}

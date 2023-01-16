<?php

namespace App\View\Components\Transfers;

use App\Abstracts\View\Component;
use App\Traits\ViewComponents;

class Script extends Component
{
    use ViewComponents;

    public const OBJECT_TYPE = 'transfer';
    public const DEFAULT_TYPE = 'transfer';
    public const DEFAULT_PLURAL_TYPE = 'transfers';

    public $model;

    public $transfer;

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
        $model = false, $transfer = false,
        string $alias = '', string $folder = '', string $file = ''
    ) {
        $this->model = ! empty($model) ? $model : $transfer;
        $this->transfer = ! empty($model) ? $model : $transfer;

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
        return view('components.transfers.script');
    }
}

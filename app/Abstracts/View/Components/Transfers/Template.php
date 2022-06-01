<?php

namespace App\Abstracts\View\Components\Transfers;

use App\Abstracts\View\Component;
use App\Traits\ViewComponents;

abstract class Template extends Component
{
    use ViewComponents;

    public $model;

    public $transfer;

    public $template;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = false, $transfer = false, string $template = ''
    ) {
        $this->model = $model;
        $this->transfer = $this->getTransfer($model, $transfer);
        $this->template = ! empty($template) ? $template : setting('transfer.template');

        // Set Parent data
        $this->setParentData();
    }

    protected function getTransfer($model, $transfer)
    {
        if (! empty($model)) {
            return $model;
        }

        if (! empty($transfer)) {
            return $transfer;
        }

        return false;
    }
}

<?php

namespace App\Abstracts\View\Components\Transfers;

use App\Abstracts\View\Component;
use App\Traits\ViewComponents;
use App\Utilities\Modules;

abstract class Template extends Component
{
    use ViewComponents;

    public $model;

    public $transfer;

    public array $payment_methods;

    public string $template;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = false, $transfer = false, array $payment_methods = [], string $template = ''
    ) {
        $this->model = $model;
        $this->transfer = $this->getTransfer($model, $transfer);
        $this->payment_methods = ($payment_methods) ?: Modules::getPaymentMethods('all');
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

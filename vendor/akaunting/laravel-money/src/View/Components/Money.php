<?php

namespace Akaunting\Money\View\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Money extends Component
{
    public function __construct(
        public mixed $amount,
        public ?string $currency = null,
        public ?bool $convert = null
    ) {
        //
    }

    /**
     * @psalm-suppress InvalidReturnType,InvalidReturnStatement
     */
    public function render(): View|Factory
    {
        return view('money::components.money');
    }
}

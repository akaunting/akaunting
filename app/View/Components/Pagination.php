<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use App\Abstracts\View\Components\Form as BaseForm;

class Pagination extends BaseForm
{
    public $items;

    /** @var array */
    public $limits;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $items,
        $limits = [],
    ) {
        $this->items = $items;
        $this->limits = $this->getLimits($limits);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.pagination');
    }

    protected function getLimits($limits)
    {
        if (! empty($limits)) {
            return $limits;
        }

        $limits = [
            '10' => '10',
            '25' => '25',
            '50' => '50',
            '100' => '100'
        ];

        return $limits;
    }
}

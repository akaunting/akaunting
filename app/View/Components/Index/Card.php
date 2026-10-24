<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;

/**
 * One record of a card page.
 *
 * The whole card leads to the record. The controls on it sit above that link rather than
 * inside it, so that selecting the record or opening its actions is not a click on the card.
 */
class Card extends Component
{
    /** @var mixed */
    public $model;

    /** @var string */
    public $href;

    /** @var string */
    public $label;

    /** @var bool */
    public $hideBulkAction;

    /**
     * How the body of the card is laid out. By default the figures sit on one line at the
     * bottom of the card; a card with more to say can lay them out on its own.
     *
     * @var string
     */
    public $bodyClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = false, string $href = '', string $label = '', bool $hideBulkAction = false,
        string $bodyClass = 'flex items-end justify-between mt-6'
    ) {
        $this->model = $model;
        $this->href = $href;

        $this->label = $this->getLabel($label);
        $this->hideBulkAction = $hideBulkAction || empty($model);
        $this->bodyClass = $bodyClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.card.index');
    }

    /**
     * What the link is called where it has nothing of its own to read, since the card fills
     * it with the record rather than with words.
     */
    protected function getLabel($label): string
    {
        if (! empty($label)) {
            return $label;
        }

        return (string) ($this->model->name ?? '');
    }
}

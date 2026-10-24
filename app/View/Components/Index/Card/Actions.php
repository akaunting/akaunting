<?php

namespace App\View\Components\Index\Card;

use App\Abstracts\View\Component;

/**
 * What a card can do, gathered behind the one button a card has room for.
 *
 * A table row shows its first few actions as icons and hides the rest; a card has no width to
 * spare beside its record, so every action is offered in the same menu.
 */
class Actions extends Component
{
    /** @var mixed */
    public $model;

    /** @var array */
    public $actions;

    /** @var string */
    public $id;

    /**
     * The name of the stack an app pushes its own entries onto.
     *
     * Keyed by record, because a stack shared by every card would print the same entries on
     * each of them.
     *
     * @var string
     */
    public $stack;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = false, array $actions = [], string $id = '', string $stack = ''
    ) {
        $this->model = $model;
        $this->actions = $this->getActions($actions);

        $this->id = ! empty($id) ? $id : 'index-card-actions-' . mt_rand(1, time());
        $this->stack = $this->getStack($stack);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.card.actions');
    }

    protected function getActions($actions): array
    {
        if (! empty($actions)) {
            return $actions;
        }

        if ($this->model && ! empty($this->model->line_actions)) {
            return $this->model->line_actions;
        }

        return [];
    }

    protected function getStack($stack): string
    {
        if (empty($stack)) {
            $stack = $this->model ? $this->model->getTable() . '_card_actions' : 'index_card_actions';
        }

        return $this->model ? $stack . '_' . $this->model->id : $stack;
    }
}

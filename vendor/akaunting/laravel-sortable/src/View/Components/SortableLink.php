<?php

namespace Akaunting\Sortable\View\Components;

use Akaunting\Sortable\Support\SortableLink as Base;
use Illuminate\View\Component;

class SortableLink extends Component
{
    /**
     * The sortablelink column.
     *
     * @var string
     */
    public $column;

    /**
     * The sortablelink title.
     *
     * @var string
     */
    public $title;

    /**
     * The sortablelink query.
     *
     * @var array
     */
    public $query;

    /**
     * The sortablelink arguments.
     *
     * @var array
     */
    public $arguments;

    /**
     * Create the component instance.
     *
     * @param  string  $title
     * @param  string  $column
     * @param  array  $parameters
     * @param  array  $attribute
     * @return void
     */
    public function __construct($column, $title, $query = [], $arguments = [])
    {
        $this->column = $column;
        $this->title = $title;
        $this->query = $query;
        $this->arguments = $arguments;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return Base::render([
            $this->column,
            $this->title,
            $this->query,
            $this->arguments,
        ]);
    }
}

<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;

/**
 * A page of records shown as cards rather than as rows.
 *
 * It carries what the head of a table used to: the bulk action that selects the page and the
 * sorting, which have nowhere else to sit once the columns are gone.
 */
class Cards extends Component
{
    /** @var int */
    public $columns;

    /** @var string */
    public $gridClass;

    /** @var array */
    public $sortable;

    /**
     * How the sorting is offered: "dropdown" gathers the columns behind one button, "inline"
     * lays them out beside each other the way the head of a table did.
     *
     * @var string
     */
    public $sortStyle;

    /** @var bool */
    public $bulkAction;

    /** @var string */
    public $sortId;

    /**
     * The column the page is sorted by, and what it is called.
     *
     * @var string
     */
    public $sortColumn;

    /** @var string */
    public $sortTitle;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        int $columns = 2, array $sortable = [], string $sortStyle = 'dropdown',
        bool $bulkAction = true, string $sortId = ''
    ) {
        $this->columns = $columns;
        $this->gridClass = $this->getGridClass($columns);

        $this->sortable = $sortable;
        $this->sortStyle = $sortStyle;
        $this->bulkAction = $bulkAction;

        $this->sortId = ! empty($sortId) ? $sortId : 'index-sort-' . mt_rand(1, time());

        $this->sortColumn = (string) request('sort', '');
        $this->sortTitle = $sortable[$this->sortColumn] ?? '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.cards');
    }

    /**
     * Spelled out rather than built from the number, because a class that is put together at
     * runtime is not in the stylesheet: nothing scanned the source for it.
     */
    protected function getGridClass($columns): string
    {
        $classes = [
            1 => '',
            2 => 'sm:grid-cols-2',
            3 => 'sm:grid-cols-2 lg:grid-cols-3',
            4 => 'sm:grid-cols-2 lg:grid-cols-4',
        ];

        return $classes[$columns] ?? $classes[2];
    }
}

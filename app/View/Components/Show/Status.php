<?php

namespace App\View\Components\Show;

use App\Abstracts\View\Component;

class Status extends Component
{
    /** @var string */
    public $backgroundColor;

    /** @var string */
    public $textColor;

    /** @var string */
    public $status;

    /** @var string */
    public $textStatus;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $backgroundColor = '', string $textColor = '',
        string $status = '', string $textStatus = ''
    ) {
        $this->backgroundColor = ! empty($backgroundColor) ? $backgroundColor : 'bg-lilac-900';
        $this->textColor = ! empty($textColor) ? $textColor : 'text-purple';
        $this->status = $status;
        $this->textStatus = $this->getTextStatus($textStatus);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.show.status');
    }

    protected function getTextStatus($textStatus)
    {
        if (! empty($textStatus)) {
            return $textStatus;
        }

        $textStatus = trans('documents.statuses.' . $this->status);

        return $textStatus;
    }
}

<?php

namespace App\View\Components\Documents\Show;

use App\Abstracts\View\Component;

class Message extends Component
{
    /** @var string */
    public $type;

    /** @var string */
    public $backgroundColor;

    /** @var string */
    public $textColor;

    /** @var string */
    public $message;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type = '', string $backgroundColor = '', string $textColor = '', string $message = ''
    ) {
        $this->type = $type;
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $textColor;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.show.message');
    }
}

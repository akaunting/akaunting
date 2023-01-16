<?php

namespace App\View\Components;

use App\Abstracts\View\Component;

class Title extends Component
{
    public $textSize;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
      string $textSize = '',

    ) {
      $this->textSize = $this->getTextSize($textSize);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.title');
    }

    protected function getTextSize($textSize)
    {
        switch ($textSize) {
            case 'short':
                $textSize = '15';
            break;
            default:
                $textSize = '25';
            break;
        }

        return $textSize;
    }
}

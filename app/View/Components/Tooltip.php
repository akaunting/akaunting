<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class Tooltip extends Component
{
    public $id;

    public $placement;

    public $tooltipPosition;

    public $backgroundColor;

    public $textColor;

    public $borderColor;

    public $message;

    public $size;

    public $whitespace;

    public $width;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $id = '', string $placement = '', string $tooltipPosition = '',
        string $backgroundColor = '', string $textColor = '', string $borderColor = '',
        string $message = '',
        string $size = '',
        string $whitespace = '',
        string $width = 'auto',
    ) {
        $this->id = $this->getId($id);
        $this->placement = $this->getPlacement($placement);
        $this->tooltipPosition = $this->getTooltipPosition($tooltipPosition);

        $this->backgroundColor = $this->getBackgroundColor($backgroundColor);
        $this->textColor = $this->getTextColor($textColor);
        $this->borderColor = $this->getBorderColor($borderColor);

        $this->message = $this->getMessage($message);
        $this->size =  $this->getSize($size);  
        $this->whitespace =  $this->getWhiteSpace($whitespace);  
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.tooltip');
    }

    protected function getId($id)
    {
        if (! empty($id)) {
            return $id;
        }

        return 'tooltip-' . Str::random(19);
    }

    protected function getPlacement($placement)
    {
        if (! empty($placement)) {
            return $placement;
        }

        return 'top';
    }

    protected function getTooltipPosition($tooltipPosition)
    {
        if (! empty($tooltipPosition)) {
            return $tooltipPosition;
        }

        switch ($this->placement) {
            case 'bottom':
                $tooltipPosition = "-top-1 before:border-b-0 before:border-r-0";
                break;
            case 'left':
                $tooltipPosition = "-right-1 before:border-b-0 before:border-l-0";
                break;
            case 'right':
                $tooltipPosition = "-left-1 before:border-t-0 before:border-r-0";
                break;
            case 'top':
            default:
                $tooltipPosition = "-bottom-1 before:border-t-0 before:border-l-0";
                break;
        }

        return $tooltipPosition;
    }

    protected function getBackgroundColor($backgroundColor)
    {
        if (! empty($backgroundColor)) {
            return $backgroundColor;
        }

        return 'bg-white';
    }

    protected function getTextColor($textColor)
    {
        if (! empty($textColor)) {
            return $textColor;
        }

        return 'text-gray-900';
    }

    protected function getBorderColor($borderColor)
    {
        if (! empty($borderColor)) {
            return $borderColor;
        }

        return 'border-gray-200';
    }

    protected function getMessage($message)
    {
        if (! empty($message)) {
            return $message;
        }

        return trans('general.na');
    }

    protected function getSize($size)
    {
        if (! empty($size)) {
            return $size;
        }

        return 'w-auto';
    }

    protected function getWhiteSpace($whitespace)
    {
        if (! empty($whitespace)) {
            return $whitespace;
        }

        return 'whitespace-normal';
    }
}

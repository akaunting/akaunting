<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class Disable extends Component
{
    public $id;

    /**
     * Tooltip position.
     *
     * @var string
     */
    public $position;

    /**
     * Tooltip in disable icon.
     *
     * @var string
     */
    public $icon;

    /**
     * Tooltip in disable icon.
     *
     * @var string
     */
    public $iconType;

    /**
     * Disabled text type name.
     *
     * @var string
     */
    public $text;

    /**
     * Full disable text.
     *
     * @var string
     */
    public $disableText;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $position = 'right', $icon = 'unpublished', $iconType = '-round', $text = '', $disableText = ''
    ) {
        $this->id = 'tooltip-disable-' . mt_rand(1, time());
        $this->position = $position;
        $this->icon = $icon;
        $this->iconType = $iconType;
        $this->disableText = $this->getDisableText($text, $disableText);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.disable');
    }

    protected function getDisableText($text, $disableText)
    {
        if (! empty($disableText)) {
            return $disableText;
        }

        return trans('general.disabled_type', ['type' => Str::lower($text)]);
    }
}

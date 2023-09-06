<?php

namespace Laravel\Prompts\Themes\Default;

use Laravel\Prompts\Spinner;

class SpinnerRenderer extends Renderer
{
    /**
     * The frames of the spinner.
     *
     * @var array<string>
     */
    protected array $frames = ['⠂', '⠒', '⠐', '⠰', '⠠', '⠤', '⠄', '⠆'];

    /**
     * The frame to render when the spinner is static.
     */
    protected string $staticFrame = '⠶';

    /**
     * The interval between frames.
     */
    protected int $interval = 75;

    /**
     * Render the spinner.
     */
    public function __invoke(Spinner $spinner): string
    {
        if ($spinner->static) {
            return $this->line(" {$this->cyan($this->staticFrame)} {$spinner->message}");
        }

        $spinner->interval = $this->interval;

        $frame = $this->frames[$spinner->count % count($this->frames)];

        return $this->line(" {$this->cyan($frame)} {$spinner->message}");
    }
}

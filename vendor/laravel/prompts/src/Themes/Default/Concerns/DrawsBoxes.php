<?php

namespace Laravel\Prompts\Themes\Default\Concerns;

use Laravel\Prompts\Prompt;

trait DrawsBoxes
{
    protected int $minWidth = 60;

    /**
     * Draw a box.
     *
     * @return $this
     */
    protected function box(
        string $title,
        string $body,
        string $footer = '',
        string $color = 'gray',
    ): self {
        $this->minWidth = min($this->minWidth, Prompt::terminal()->cols() - 6);

        $bodyLines = collect(explode(PHP_EOL, $body));
        $footerLines = collect(explode(PHP_EOL, $footer))->filter();
        $width = $this->longest(
            $bodyLines
                ->merge($footerLines)
                ->push($title)
                ->toArray()
        );

        $topBorder = str_repeat('─', $width - mb_strwidth($this->stripEscapeSequences($title)));
        $bottomBorder = str_repeat('─', $width + 2);

        $this->line("{$this->{$color}(' ┌')} {$title} {$this->{$color}($topBorder.'┐')}");

        $bodyLines->each(function ($line) use ($width, $color) {
            $this->line("{$this->{$color}(' │')} {$this->pad($line, $width)} {$this->{$color}('│')}");
        });

        if ($footerLines->isNotEmpty()) {
            $this->line($this->{$color}(' ├'.$bottomBorder.'┤'));

            $footerLines->each(function ($line) use ($width, $color) {
                $this->line("{$this->{$color}(' │')} {$this->pad($line, $width)} {$this->{$color}('│')}");
            });
        }

        $this->line($this->{$color}(' └'.$bottomBorder.'┘'));

        return $this;
    }

    /**
     * Get the length of the longest line.
     *
     * @param  array<string>  $lines
     */
    protected function longest(array $lines, int $padding = 0): int
    {
        return max(
            $this->minWidth,
            collect($lines)
                ->map(fn ($line) => mb_strwidth($this->stripEscapeSequences($line)) + $padding)
                ->max()
        );
    }

    /**
     * Pad text ignoring ANSI escape sequences.
     */
    protected function pad(string $text, int $length): string
    {
        $rightPadding = str_repeat(' ', max(0, $length - mb_strwidth($this->stripEscapeSequences($text))));

        return "{$text}{$rightPadding}";
    }

    /**
     * Strip ANSI escape sequences from the given text.
     */
    protected function stripEscapeSequences(string $text): string
    {
        $text = preg_replace("/\e[^m]*m/", '', $text);

        return preg_replace("/<(?:(?:[fb]g|options)=[a-z,;]+)+>(.*?)<\/>/i", '$1', $text);
    }
}

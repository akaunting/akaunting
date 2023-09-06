<?php

namespace Laravel\Prompts\Themes\Default;

use Laravel\Prompts\SelectPrompt;

class SelectPromptRenderer extends Renderer
{
    use Concerns\DrawsBoxes;
    use Concerns\DrawsScrollbars;

    /**
     * Render the select prompt.
     */
    public function __invoke(SelectPrompt $prompt): string
    {
        $prompt->scroll = min($prompt->scroll, $prompt->terminal()->lines() - 5);
        $maxWidth = $prompt->terminal()->cols() - 6;

        return match ($prompt->state) {
            'submit' => $this
                ->box(
                    $this->dim($this->truncate($prompt->label, $prompt->terminal()->cols() - 6)),
                    $this->truncate($prompt->label(), $maxWidth),
                ),

            'cancel' => $this
                ->box(
                    $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    $this->renderOptions($prompt),
                    color: 'red',
                )
                ->error('Cancelled.'),

            'error' => $this
                ->box(
                    $this->truncate($prompt->label, $prompt->terminal()->cols() - 6),
                    $this->renderOptions($prompt),
                    color: 'yellow',
                )
                ->warning($this->truncate($prompt->error, $prompt->terminal()->cols() - 5)),

            default => $this
                ->box(
                    $this->cyan($this->truncate($prompt->label, $prompt->terminal()->cols() - 6)),
                    $this->renderOptions($prompt),
                )
                ->when(
                    $prompt->hint,
                    fn () => $this->hint($prompt->hint),
                    fn () => $this->newLine() // Space for errors
                ),
        };
    }

    /**
     * Render the options.
     */
    protected function renderOptions(SelectPrompt $prompt): string
    {
        return $this->scrollbar(
            collect($prompt->visible())
                ->map(fn ($label) => $this->truncate($label, $prompt->terminal()->cols() - 12))
                ->map(function ($label, $key) use ($prompt) {
                    $index = array_search($key, array_keys($prompt->options));

                    if ($prompt->state === 'cancel') {
                        return $this->dim($prompt->highlighted === $index
                            ? "› ● {$this->strikethrough($label)}  "
                            : "  ○ {$this->strikethrough($label)}  "
                        );
                    }

                    return $prompt->highlighted === $index
                        ? "{$this->cyan('›')} {$this->cyan('●')} {$label}  "
                        : "  {$this->dim('○')} {$this->dim($label)}  ";
                })
                ->values(),
            $prompt->firstVisible,
            $prompt->scroll,
            count($prompt->options),
            min($this->longest($prompt->options, padding: 6), $prompt->terminal()->cols() - 6),
            $prompt->state === 'cancel' ? 'dim' : 'cyan'
        )->implode(PHP_EOL);
    }
}

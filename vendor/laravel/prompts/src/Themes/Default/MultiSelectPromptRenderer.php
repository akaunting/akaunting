<?php

namespace Laravel\Prompts\Themes\Default;

use Laravel\Prompts\MultiSelectPrompt;

class MultiSelectPromptRenderer extends Renderer
{
    use Concerns\DrawsBoxes;
    use Concerns\DrawsScrollbars;

    /**
     * Render the multiselect prompt.
     */
    public function __invoke(MultiSelectPrompt $prompt): string
    {
        $prompt->scroll = min($prompt->scroll, $prompt->terminal()->lines() - 5);

        return match ($prompt->state) {
            'submit' => $this
                ->box(
                    $this->dim($this->truncate($prompt->label, $prompt->terminal()->cols() - 6)),
                    $this->renderSelectedOptions($prompt)
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
    protected function renderOptions(MultiSelectPrompt $prompt): string
    {
        return $this->scrollbar(
            collect($prompt->visible())
                ->map(fn ($label) => $this->truncate($label, $prompt->terminal()->cols() - 12))
                ->map(function ($label, $key) use ($prompt) {
                    $index = array_search($key, array_keys($prompt->options));
                    $active = $index === $prompt->highlighted;
                    if (array_is_list($prompt->options)) {
                        $value = $prompt->options[$index];
                    } else {
                        $value = array_keys($prompt->options)[$index];
                    }
                    $selected = in_array($value, $prompt->value());

                    if ($prompt->state === 'cancel') {
                        return $this->dim(match (true) {
                            $active && $selected => "› ◼ {$this->strikethrough($label)}  ",
                            $active => "› ◻ {$this->strikethrough($label)}  ",
                            $selected => "  ◼ {$this->strikethrough($label)}  ",
                            default => "  ◻ {$this->strikethrough($label)}  ",
                        });
                    }

                    return match (true) {
                        $active && $selected => "{$this->cyan('› ◼')} {$label}  ",
                        $active => "{$this->cyan('›')} ◻ {$label}  ",
                        $selected => "  {$this->cyan('◼')} {$this->dim($label)}  ",
                        default => "  {$this->dim('◻')} {$this->dim($label)}  ",
                    };
                })
                ->values(),
            $prompt->firstVisible,
            $prompt->scroll,
            count($prompt->options),
            min($this->longest($prompt->options, padding: 6), $prompt->terminal()->cols() - 6),
            $prompt->state === 'cancel' ? 'dim' : 'cyan'
        )->implode(PHP_EOL);
    }

    /**
     * Render the selected options.
     */
    protected function renderSelectedOptions(MultiSelectPrompt $prompt): string
    {
        if (count($prompt->labels()) === 0) {
            return $this->gray('None');
        }

        return implode("\n", array_map(
            fn ($label) => $this->truncate($label, $prompt->terminal()->cols() - 6),
            $prompt->labels()
        ));
    }
}

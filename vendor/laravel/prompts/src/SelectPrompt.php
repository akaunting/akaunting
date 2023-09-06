<?php

namespace Laravel\Prompts;

use Closure;
use Illuminate\Support\Collection;

class SelectPrompt extends Prompt
{
    /**
     * The index of the highlighted option.
     */
    public int $highlighted = 0;

    /**
     * The index of the first visible option.
     */
    public int $firstVisible = 0;

    /**
     * The options for the select prompt.
     *
     * @var array<int|string, string>
     */
    public array $options;

    /**
     * Create a new SelectPrompt instance.
     *
     * @param  array<int|string, string>|Collection<int|string, string>  $options
     */
    public function __construct(
        public string $label,
        array|Collection $options,
        public int|string|null $default = null,
        public int $scroll = 5,
        public ?Closure $validate = null,
        public string $hint = ''
    ) {
        $this->options = $options instanceof Collection ? $options->all() : $options;

        if ($this->default) {
            if (array_is_list($this->options)) {
                $this->highlighted = array_search($this->default, $this->options) ?: 0;
            } else {
                $this->highlighted = array_search($this->default, array_keys($this->options)) ?: 0;
            }
        }

        $this->on('key', fn ($key) => match ($key) {
            Key::UP, Key::UP_ARROW, Key::LEFT, Key::LEFT_ARROW, Key::SHIFT_TAB, 'k', 'h' => $this->highlightPrevious(),
            Key::DOWN, Key::DOWN_ARROW, Key::RIGHT, Key::RIGHT_ARROW, Key::TAB, 'j', 'l' => $this->highlightNext(),
            Key::ENTER => $this->submit(),
            default => null,
        });
    }

    /**
     * Get the selected value.
     */
    public function value(): int|string|null
    {
        if (array_is_list($this->options)) {
            return $this->options[$this->highlighted] ?? null;
        } else {
            return array_keys($this->options)[$this->highlighted];
        }
    }

    /**
     * Get the selected label.
     */
    public function label(): ?string
    {
        if (array_is_list($this->options)) {
            return $this->options[$this->highlighted] ?? null;
        } else {
            return $this->options[array_keys($this->options)[$this->highlighted]] ?? null;
        }
    }

    /**
     * The currently visible options.
     *
     * @return array<int|string, string>
     */
    public function visible(): array
    {
        return array_slice($this->options, $this->firstVisible, $this->scroll, preserve_keys: true);
    }

    /**
     * Highlight the previous entry, or wrap around to the last entry.
     */
    protected function highlightPrevious(): void
    {
        $this->highlighted = $this->highlighted === 0 ? count($this->options) - 1 : $this->highlighted - 1;

        if ($this->highlighted < $this->firstVisible) {
            $this->firstVisible--;
        } elseif ($this->highlighted === count($this->options) - 1) {
            $this->firstVisible = count($this->options) - min($this->scroll, count($this->options));
        }
    }

    /**
     * Highlight the next entry, or wrap around to the first entry.
     */
    protected function highlightNext(): void
    {
        $this->highlighted = $this->highlighted === count($this->options) - 1 ? 0 : $this->highlighted + 1;

        if ($this->highlighted > $this->firstVisible + $this->scroll - 1) {
            $this->firstVisible++;
        } elseif ($this->highlighted === 0) {
            $this->firstVisible = 0;
        }
    }
}

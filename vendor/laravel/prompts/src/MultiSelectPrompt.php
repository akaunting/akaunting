<?php

namespace Laravel\Prompts;

use Closure;
use Illuminate\Support\Collection;

class MultiSelectPrompt extends Prompt
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
     * The options for the multi-select prompt.
     *
     * @var array<int|string, string>
     */
    public array $options;

    /**
     * The default values the multi-select prompt.
     *
     * @var array<int|string>
     */
    public array $default;

    /**
     * The selected values.
     *
     * @var array<int|string>
     */
    protected array $values = [];

    /**
     * Create a new MultiSelectPrompt instance.
     *
     * @param  array<int|string, string>|Collection<int|string, string>  $options
     * @param  array<int|string>|Collection<int, int|string>  $default
     */
    public function __construct(
        public string $label,
        array|Collection $options,
        array|Collection $default = [],
        public int $scroll = 5,
        public bool|string $required = false,
        public ?Closure $validate = null,
        public string $hint = ''
    ) {
        $this->options = $options instanceof Collection ? $options->all() : $options;
        $this->default = $default instanceof Collection ? $default->all() : $default;
        $this->values = $this->default;

        $this->on('key', fn ($key) => match ($key) {
            Key::UP, Key::UP_ARROW, Key::LEFT, Key::LEFT_ARROW, Key::SHIFT_TAB, 'k', 'h' => $this->highlightPrevious(),
            Key::DOWN, Key::DOWN_ARROW, Key::RIGHT, Key::RIGHT_ARROW, Key::TAB, 'j', 'l' => $this->highlightNext(),
            Key::SPACE => $this->toggleHighlighted(),
            Key::ENTER => $this->submit(),
            default => null,
        });
    }

    /**
     * Get the selected values.
     *
     * @return array<int|string>
     */
    public function value(): array
    {
        return array_values($this->values);
    }

    /**
     * Get the selected labels.
     *
     * @return array<string>
     */
    public function labels(): array
    {
        if (array_is_list($this->options)) {
            return array_map(fn ($value) => (string) $value, $this->values);
        }

        return array_values(array_intersect_key($this->options, array_flip($this->values)));
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
     * Check whether the value is currently highlighted.
     */
    public function isHighlighted(string $value): bool
    {
        if (array_is_list($this->options)) {
            return $this->options[$this->highlighted] === $value;
        }

        return array_keys($this->options)[$this->highlighted] === $value;
    }

    /**
     * Check whether the value is currently selected.
     */
    public function isSelected(string $value): bool
    {
        return in_array($value, $this->values);
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

    /**
     * Toggle the highlighted entry.
     */
    protected function toggleHighlighted(): void
    {
        $value = array_is_list($this->options)
            ? $this->options[$this->highlighted]
            : array_keys($this->options)[$this->highlighted];

        if (in_array($value, $this->values)) {
            $this->values = array_filter($this->values, fn ($v) => $v !== $value);
        } else {
            $this->values[] = $value;
        }
    }
}

<?php

namespace Laravel\Prompts;

use Closure;

class SearchPrompt extends Prompt
{
    use Concerns\Truncation;
    use Concerns\TypedValue;

    /**
     * The index of the highlighted option.
     */
    public ?int $highlighted = null;

    /**
     * The index of the first visible option.
     */
    public int $firstVisible = 0;

    /**
     * The cached matches.
     *
     * @var array<int|string, string>|null
     */
    protected ?array $matches = null;

    /**
     * Create a new SearchPrompt instance.
     *
     * @param  Closure(string): array<int|string, string>  $options
     */
    public function __construct(
        public string $label,
        public Closure $options,
        public string $placeholder = '',
        public int $scroll = 5,
        public ?Closure $validate = null,
        public string $hint = ''
    ) {
        $this->trackTypedValue(submit: false);

        $this->on('key', fn ($key) => match ($key) {
            Key::UP, Key::UP_ARROW, Key::SHIFT_TAB => $this->highlightPrevious(),
            Key::DOWN, Key::DOWN_ARROW, Key::TAB => $this->highlightNext(),
            Key::ENTER => $this->highlighted !== null ? $this->submit() : $this->search(),
            Key::LEFT, Key::LEFT_ARROW, Key::RIGHT, Key::RIGHT_ARROW => $this->highlighted = null,
            default => $this->search(),
        });
    }

    /**
     * Perform the search.
     */
    protected function search(): void
    {
        $this->state = 'searching';
        $this->highlighted = null;
        $this->render();
        $this->matches = null;
        $this->state = 'active';
    }

    /**
     * Get the entered value with a virtual cursor.
     */
    public function valueWithCursor(int $maxWidth): string
    {
        if ($this->highlighted !== null) {
            return $this->typedValue === ''
                ? $this->dim($this->truncate($this->placeholder, $maxWidth))
                : $this->truncate($this->typedValue, $maxWidth);
        }

        if ($this->typedValue === '') {
            return $this->dim($this->addCursor($this->placeholder, 0, $maxWidth));
        }

        return $this->addCursor($this->typedValue, $this->cursorPosition, $maxWidth);
    }

    /**
     * Get options that match the input.
     *
     * @return array<string>
     */
    public function matches(): array
    {
        if (is_array($this->matches)) {
            return $this->matches;
        }

        return $this->matches = ($this->options)($this->typedValue);
    }

    /**
     * The currently visible matches.
     *
     * @return array<string>
     */
    public function visible(): array
    {
        return array_slice($this->matches(), $this->firstVisible, $this->scroll, preserve_keys: true);
    }

    /**
     * Highlight the previous entry, or wrap around to the last entry.
     */
    protected function highlightPrevious(): void
    {
        if ($this->matches === []) {
            $this->highlighted = null;
        } elseif ($this->highlighted === null) {
            $this->highlighted = count($this->matches) - 1;
        } elseif ($this->highlighted === 0) {
            $this->highlighted = null;
        } else {
            $this->highlighted = $this->highlighted - 1;
        }

        if ($this->highlighted < $this->firstVisible) {
            $this->firstVisible--;
        } elseif ($this->highlighted === count($this->matches) - 1) {
            $this->firstVisible = count($this->matches) - min($this->scroll, count($this->matches));
        }
    }

    /**
     * Highlight the next entry, or wrap around to the first entry.
     */
    protected function highlightNext(): void
    {
        if ($this->matches === []) {
            $this->highlighted = null;
        } elseif ($this->highlighted === null) {
            $this->highlighted = 0;
        } else {
            $this->highlighted = $this->highlighted === count($this->matches) - 1 ? null : $this->highlighted + 1;
        }

        if ($this->highlighted > $this->firstVisible + $this->scroll - 1) {
            $this->firstVisible++;
        } elseif ($this->highlighted === 0 || $this->highlighted === null) {
            $this->firstVisible = 0;
        }
    }

    /**
     * Get the current search query.
     */
    public function searchValue(): string
    {
        return $this->typedValue;
    }

    /**
     * Get the selected value.
     */
    public function value(): int|string|null
    {
        if ($this->matches === null || $this->highlighted === null) {
            return null;
        }

        return array_is_list($this->matches)
            ? $this->matches[$this->highlighted]
            : array_keys($this->matches)[$this->highlighted];
    }

    /**
     * Get the selected label.
     */
    public function label(): ?string
    {
        return $this->matches[array_keys($this->matches)[$this->highlighted]] ?? null;
    }
}

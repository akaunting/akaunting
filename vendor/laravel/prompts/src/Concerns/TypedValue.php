<?php

namespace Laravel\Prompts\Concerns;

use Laravel\Prompts\Key;

trait TypedValue
{
    /**
     * The value that has been typed.
     */
    protected string $typedValue = '';

    /**
     * The position of the virtual cursor.
     */
    protected int $cursorPosition = 0;

    /**
     * Track the value as the user types.
     */
    protected function trackTypedValue(string $default = '', bool $submit = true): void
    {
        $this->typedValue = $default;

        if ($this->typedValue) {
            $this->cursorPosition = mb_strlen($this->typedValue);
        }

        $this->on('key', function ($key) use ($submit) {
            if ($key[0] === "\e") {
                match ($key) {
                    Key::LEFT, Key::LEFT_ARROW => $this->cursorPosition = max(0, $this->cursorPosition - 1),
                    Key::RIGHT, Key::RIGHT_ARROW => $this->cursorPosition = min(mb_strlen($this->typedValue), $this->cursorPosition + 1),
                    Key::DELETE => $this->typedValue = mb_substr($this->typedValue, 0, $this->cursorPosition).mb_substr($this->typedValue, $this->cursorPosition + 1),
                    default => null,
                };

                return;
            }

            // Keys may be buffered.
            foreach (mb_str_split($key) as $key) {
                if ($key === Key::ENTER && $submit) {
                    $this->submit();

                    return;
                } elseif ($key === Key::BACKSPACE) {
                    if ($this->cursorPosition === 0) {
                        return;
                    }

                    $this->typedValue = mb_substr($this->typedValue, 0, $this->cursorPosition - 1).mb_substr($this->typedValue, $this->cursorPosition);
                    $this->cursorPosition--;
                } elseif (ord($key) >= 32) {
                    $this->typedValue = mb_substr($this->typedValue, 0, $this->cursorPosition).$key.mb_substr($this->typedValue, $this->cursorPosition);
                    $this->cursorPosition++;
                }
            }
        });
    }

    /**
     * Get the value of the prompt.
     */
    public function value(): string
    {
        return $this->typedValue;
    }

    /**
     * Add a virtual cursor to the value and truncate if necessary.
     */
    protected function addCursor(string $value, int $cursorPosition, int $maxWidth): string
    {
        $before = mb_substr($value, 0, $cursorPosition);
        $current = mb_substr($value, $cursorPosition, 1);
        $after = mb_substr($value, $cursorPosition + 1);

        $cursor = mb_strlen($current) ? $current : ' ';

        $spaceBefore = $maxWidth - mb_strwidth($cursor) - (mb_strwidth($after) > 0 ? 1 : 0);
        [$truncatedBefore, $wasTruncatedBefore] = mb_strwidth($before) > $spaceBefore
            ? [$this->trimWidthBackwards($before, 0, $spaceBefore - 1), true]
            : [$before, false];

        $spaceAfter = $maxWidth - ($wasTruncatedBefore ? 1 : 0) - mb_strwidth($truncatedBefore) - mb_strwidth($cursor);
        [$truncatedAfter, $wasTruncatedAfter] = mb_strwidth($after) > $spaceAfter
            ? [mb_strimwidth($after, 0, $spaceAfter - 1), true]
            : [$after, false];

        return ($wasTruncatedBefore ? $this->dim('…') : '')
            .$truncatedBefore
            .$this->inverse($cursor)
            .$truncatedAfter
            .($wasTruncatedAfter ? $this->dim('…') : '');
    }

    /**
     * Get a truncated string with the specified width from the end.
     */
    private function trimWidthBackwards(string $string, int $start, int $width): string
    {
        $reversed = implode('', array_reverse(mb_str_split($string, 1)));

        $trimmed = mb_strimwidth($reversed, $start, $width);

        return implode('', array_reverse(mb_str_split($trimmed, 1)));
    }
}

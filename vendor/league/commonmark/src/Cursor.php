<?php

declare(strict_types=1);

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark;

use League\CommonMark\Exception\UnexpectedEncodingException;

class Cursor
{
    public const INDENT_LEVEL = 4;

    /**
     * @var string
     */
    private $line;

    /**
     * @var int
     */
    private $length;

    /**
     * @var int
     *
     * It's possible for this to be 1 char past the end, meaning we've parsed all chars and have
     * reached the end.  In this state, any character-returning method MUST return null.
     */
    private $currentPosition = 0;

    /**
     * @var int
     */
    private $column = 0;

    /**
     * @var int
     */
    private $indent = 0;

    /**
     * @var int
     */
    private $previousPosition = 0;

    /**
     * @var int|null
     */
    private $nextNonSpaceCache;

    /**
     * @var bool
     */
    private $partiallyConsumedTab = false;

    /**
     * @var bool
     */
    private $lineContainsTabs;

    /**
     * @var bool
     */
    private $isMultibyte;

    /**
     * @var array<int, string>
     */
    private $charCache = [];

    /**
     * @param string $line The line being parsed (ASCII or UTF-8)
     */
    public function __construct(string $line)
    {
        if (!\mb_check_encoding($line, 'UTF-8')) {
            throw new UnexpectedEncodingException('Unexpected encoding - UTF-8 or ASCII was expected');
        }

        $this->line = $line;
        $this->length = \mb_strlen($line, 'UTF-8') ?: 0;
        $this->isMultibyte = $this->length !== \strlen($line);
        $this->lineContainsTabs = false !== \strpos($line, "\t");
    }

    /**
     * Returns the position of the next character which is not a space (or tab)
     *
     * @return int
     */
    public function getNextNonSpacePosition(): int
    {
        if ($this->nextNonSpaceCache !== null) {
            return $this->nextNonSpaceCache;
        }

        $i = $this->currentPosition;
        $cols = $this->column;

        while (($c = $this->getCharacter($i)) !== null) {
            if ($c === ' ') {
                $i++;
                $cols++;
            } elseif ($c === "\t") {
                $i++;
                $cols += (4 - ($cols % 4));
            } else {
                break;
            }
        }

        $nextNonSpace = ($c === null) ? $this->length : $i;
        $this->indent = $cols - $this->column;

        return $this->nextNonSpaceCache = $nextNonSpace;
    }

    /**
     * Returns the next character which isn't a space (or tab)
     *
     * @return string
     */
    public function getNextNonSpaceCharacter(): ?string
    {
        return $this->getCharacter($this->getNextNonSpacePosition());
    }

    /**
     * Calculates the current indent (number of spaces after current position)
     *
     * @return int
     */
    public function getIndent(): int
    {
        if ($this->nextNonSpaceCache === null) {
            $this->getNextNonSpacePosition();
        }

        return $this->indent;
    }

    /**
     * Whether the cursor is indented to INDENT_LEVEL
     *
     * @return bool
     */
    public function isIndented(): bool
    {
        return $this->getIndent() >= self::INDENT_LEVEL;
    }

    /**
     * @param int|null $index
     *
     * @return string|null
     */
    public function getCharacter(?int $index = null): ?string
    {
        if ($index === null) {
            $index = $this->currentPosition;
        }

        // Index out-of-bounds, or we're at the end
        if ($index < 0 || $index >= $this->length) {
            return null;
        }

        if ($this->isMultibyte) {
            if (isset($this->charCache[$index])) {
                return $this->charCache[$index];
            }

            return $this->charCache[$index] = \mb_substr($this->line, $index, 1, 'UTF-8');
        }

        return $this->line[$index];
    }

    /**
     * Returns the next character (or null, if none) without advancing forwards
     *
     * @param int $offset
     *
     * @return string|null
     */
    public function peek(int $offset = 1): ?string
    {
        return $this->getCharacter($this->currentPosition + $offset);
    }

    /**
     * Whether the remainder is blank
     *
     * @return bool
     */
    public function isBlank(): bool
    {
        return $this->nextNonSpaceCache === $this->length || $this->getNextNonSpacePosition() === $this->length;
    }

    /**
     * Move the cursor forwards
     *
     * @return void
     */
    public function advance()
    {
        $this->advanceBy(1);
    }

    /**
     * Move the cursor forwards
     *
     * @param int  $characters       Number of characters to advance by
     * @param bool $advanceByColumns Whether to advance by columns instead of spaces
     *
     * @return void
     */
    public function advanceBy(int $characters, bool $advanceByColumns = false)
    {
        if ($characters === 0) {
            $this->previousPosition = $this->currentPosition;

            return;
        }

        $this->previousPosition = $this->currentPosition;
        $this->nextNonSpaceCache = null;

        // Optimization to avoid tab handling logic if we have no tabs
        if (!$this->lineContainsTabs || false === \strpos(
            $nextFewChars = $this->isMultibyte ?
                \mb_substr($this->line, $this->currentPosition, $characters, 'UTF-8') :
                \substr($this->line, $this->currentPosition, $characters),
            "\t"
        )) {
            $length = \min($characters, $this->length - $this->currentPosition);
            $this->partiallyConsumedTab = false;
            $this->currentPosition += $length;
            $this->column += $length;

            return;
        }

        if ($characters === 1 && !empty($nextFewChars)) {
            $asArray = [$nextFewChars];
        } elseif ($this->isMultibyte) {
            /** @var string[] $asArray */
            $asArray = \preg_split('//u', $nextFewChars, -1, \PREG_SPLIT_NO_EMPTY);
        } else {
            $asArray = \str_split($nextFewChars);
        }

        foreach ($asArray as $relPos => $c) {
            if ($c === "\t") {
                $charsToTab = 4 - ($this->column % 4);
                if ($advanceByColumns) {
                    $this->partiallyConsumedTab = $charsToTab > $characters;
                    $charsToAdvance = $charsToTab > $characters ? $characters : $charsToTab;
                    $this->column += $charsToAdvance;
                    $this->currentPosition += $this->partiallyConsumedTab ? 0 : 1;
                    $characters -= $charsToAdvance;
                } else {
                    $this->partiallyConsumedTab = false;
                    $this->column += $charsToTab;
                    $this->currentPosition++;
                    $characters--;
                }
            } else {
                $this->partiallyConsumedTab = false;
                $this->currentPosition++;
                $this->column++;
                $characters--;
            }

            if ($characters <= 0) {
                break;
            }
        }
    }

    /**
     * Advances the cursor by a single space or tab, if present
     *
     * @return bool
     */
    public function advanceBySpaceOrTab(): bool
    {
        $character = $this->getCharacter();

        if ($character === ' ' || $character === "\t") {
            $this->advanceBy(1, true);

            return true;
        }

        return false;
    }

    /**
     * Parse zero or more space/tab characters
     *
     * @return int Number of positions moved
     */
    public function advanceToNextNonSpaceOrTab(): int
    {
        $newPosition = $this->getNextNonSpacePosition();
        $this->advanceBy($newPosition - $this->currentPosition);
        $this->partiallyConsumedTab = false;

        return $this->currentPosition - $this->previousPosition;
    }

    /**
     * Parse zero or more space characters, including at most one newline.
     *
     * Tab characters are not parsed with this function.
     *
     * @return int Number of positions moved
     */
    public function advanceToNextNonSpaceOrNewline(): int
    {
        $remainder = $this->getRemainder();

        // Optimization: Avoid the regex if we know there are no spaces or newlines
        if (empty($remainder) || ($remainder[0] !== ' ' && $remainder[0] !== "\n")) {
            $this->previousPosition = $this->currentPosition;

            return 0;
        }

        $matches = [];
        \preg_match('/^ *(?:\n *)?/', $remainder, $matches, \PREG_OFFSET_CAPTURE);

        // [0][0] contains the matched text
        // [0][1] contains the index of that match
        $increment = $matches[0][1] + \strlen($matches[0][0]);

        $this->advanceBy($increment);

        return $this->currentPosition - $this->previousPosition;
    }

    /**
     * Move the position to the very end of the line
     *
     * @return int The number of characters moved
     */
    public function advanceToEnd(): int
    {
        $this->previousPosition = $this->currentPosition;
        $this->nextNonSpaceCache = null;

        $this->currentPosition = $this->length;

        return $this->currentPosition - $this->previousPosition;
    }

    public function getRemainder(): string
    {
        if ($this->currentPosition >= $this->length) {
            return '';
        }

        $prefix = '';
        $position = $this->currentPosition;
        if ($this->partiallyConsumedTab) {
            $position++;
            $charsToTab = 4 - ($this->column % 4);
            $prefix = \str_repeat(' ', $charsToTab);
        }

        $subString = $this->isMultibyte ?
            \mb_substr($this->line, $position, null, 'UTF-8') :
            \substr($this->line, $position);

        return $prefix . $subString;
    }

    public function getLine(): string
    {
        return $this->line;
    }

    public function isAtEnd(): bool
    {
        return $this->currentPosition >= $this->length;
    }

    /**
     * Try to match a regular expression
     *
     * Returns the matching text and advances to the end of that match
     *
     * @param string $regex
     *
     * @return string|null
     */
    public function match(string $regex): ?string
    {
        $subject = $this->getRemainder();

        if (!\preg_match($regex, $subject, $matches, \PREG_OFFSET_CAPTURE)) {
            return null;
        }

        // $matches[0][0] contains the matched text
        // $matches[0][1] contains the index of that match

        if ($this->isMultibyte) {
            // PREG_OFFSET_CAPTURE always returns the byte offset, not the char offset, which is annoying
            $offset = \mb_strlen(\substr($subject, 0, $matches[0][1]), 'UTF-8');
            $matchLength = \mb_strlen($matches[0][0], 'UTF-8');
        } else {
            $offset = $matches[0][1];
            $matchLength = \strlen($matches[0][0]);
        }

        // [0][0] contains the matched text
        // [0][1] contains the index of that match
        $this->advanceBy($offset + $matchLength);

        return $matches[0][0];
    }

    /**
     * Encapsulates the current state of this cursor in case you need to rollback later.
     *
     * WARNING: Do not parse or use the return value for ANYTHING except for
     * passing it back into restoreState(), as the number of values and their
     * contents may change in any future release without warning.
     *
     * @return array<mixed>
     */
    public function saveState()
    {
        return [
            $this->currentPosition,
            $this->previousPosition,
            $this->nextNonSpaceCache,
            $this->indent,
            $this->column,
            $this->partiallyConsumedTab,
        ];
    }

    /**
     * Restore the cursor to a previous state.
     *
     * Pass in the value previously obtained by calling saveState().
     *
     * @param array<mixed> $state
     *
     * @return void
     */
    public function restoreState($state)
    {
        list(
            $this->currentPosition,
            $this->previousPosition,
            $this->nextNonSpaceCache,
            $this->indent,
            $this->column,
            $this->partiallyConsumedTab,
          ) = $state;
    }

    public function getPosition(): int
    {
        return $this->currentPosition;
    }

    public function getPreviousText(): string
    {
        return \mb_substr($this->line, $this->previousPosition, $this->currentPosition - $this->previousPosition, 'UTF-8');
    }

    public function getSubstring(int $start, ?int $length = null): string
    {
        if ($this->isMultibyte) {
            return \mb_substr($this->line, $start, $length, 'UTF-8');
        } elseif ($length !== null) {
            return \substr($this->line, $start, $length);
        }

        return \substr($this->line, $start);
    }

    public function getColumn(): int
    {
        return $this->column;
    }
}

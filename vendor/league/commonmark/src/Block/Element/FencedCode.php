<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Block\Element;

use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Util\RegexHelper;

class FencedCode extends AbstractStringContainerBlock
{
    /**
     * @var string
     */
    protected $info;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var string
     */
    protected $char;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @param int    $length
     * @param string $char
     * @param int    $offset
     */
    public function __construct(int $length, string $char, int $offset)
    {
        parent::__construct();

        $this->length = $length;
        $this->char = $char;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * @return string[]
     */
    public function getInfoWords(): array
    {
        return \preg_split('/\s+/', $this->info) ?: [];
    }

    /**
     * @return string
     */
    public function getChar(): string
    {
        return $this->char;
    }

    /**
     * @param string $char
     *
     * @return $this
     */
    public function setChar(string $char): self
    {
        $this->char = $char;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     *
     * @return $this
     */
    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function canContain(AbstractBlock $block): bool
    {
        return false;
    }

    public function isCode(): bool
    {
        return true;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        if ($this->length === -1) {
            if ($cursor->isBlank()) {
                $this->lastLineBlank = true;
            }

            return false;
        }

        // Skip optional spaces of fence offset
        $cursor->match('/^ {0,' . $this->offset . '}/');

        return true;
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        // first line becomes info string
        $firstLine = $this->strings->first();
        if ($firstLine === false) {
            $firstLine = '';
        }

        $this->info = RegexHelper::unescape(\trim($firstLine));

        if ($this->strings->count() === 1) {
            $this->finalStringContents = '';
        } else {
            $this->finalStringContents = \implode("\n", $this->strings->slice(1)) . "\n";
        }
    }

    public function handleRemainingContents(ContextInterface $context, Cursor $cursor)
    {
        /** @var self $container */
        $container = $context->getContainer();

        // check for closing code fence
        if ($cursor->getIndent() <= 3 && $cursor->getNextNonSpaceCharacter() === $container->getChar()) {
            $match = RegexHelper::matchAll('/^(?:`{3,}|~{3,})(?= *$)/', $cursor->getLine(), $cursor->getNextNonSpacePosition());
            if ($match !== null && \strlen($match[0]) >= $container->getLength()) {
                // don't add closing fence to container; instead, close it:
                $this->setLength(-1); // -1 means we've passed closer

                return;
            }
        }

        $container->addLine($cursor->getRemainder());
    }

    public function shouldLastLineBeBlank(Cursor $cursor, int $currentLineNumber): bool
    {
        return false;
    }
}

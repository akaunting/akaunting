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

namespace League\CommonMark\Delimiter;

use League\CommonMark\Inline\Element\AbstractStringContainer;

final class Delimiter implements DelimiterInterface
{
    /** @var string */
    private $char;

    /** @var int */
    private $length;

    /** @var int */
    private $originalLength;

    /** @var AbstractStringContainer */
    private $inlineNode;

    /** @var DelimiterInterface|null */
    private $previous;

    /** @var DelimiterInterface|null */
    private $next;

    /** @var bool */
    private $canOpen;

    /** @var bool */
    private $canClose;

    /** @var bool */
    private $active;

    /** @var int|null */
    private $index;

    /**
     * @param string                  $char
     * @param int                     $numDelims
     * @param AbstractStringContainer $node
     * @param bool                    $canOpen
     * @param bool                    $canClose
     * @param int|null                $index
     */
    public function __construct(string $char, int $numDelims, AbstractStringContainer $node, bool $canOpen, bool $canClose, ?int $index = null)
    {
        $this->char = $char;
        $this->length = $numDelims;
        $this->originalLength = $numDelims;
        $this->inlineNode = $node;
        $this->canOpen = $canOpen;
        $this->canClose = $canClose;
        $this->active = true;
        $this->index = $index;
    }

    public function canClose(): bool
    {
        return $this->canClose;
    }

    /**
     * @param bool $canClose
     *
     * @return void
     */
    public function setCanClose(bool $canClose)
    {
        $this->canClose = $canClose;
    }

    public function canOpen(): bool
    {
        return $this->canOpen;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function getChar(): string
    {
        return $this->char;
    }

    public function getIndex(): ?int
    {
        return $this->index;
    }

    public function getNext(): ?DelimiterInterface
    {
        return $this->next;
    }

    public function setNext(?DelimiterInterface $next)
    {
        $this->next = $next;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length)
    {
        $this->length = $length;
    }

    public function getOriginalLength(): int
    {
        return $this->originalLength;
    }

    public function getInlineNode(): AbstractStringContainer
    {
        return $this->inlineNode;
    }

    public function getPrevious(): ?DelimiterInterface
    {
        return $this->previous;
    }

    public function setPrevious(?DelimiterInterface $previous): DelimiterInterface
    {
        $this->previous = $previous;

        return $this;
    }
}

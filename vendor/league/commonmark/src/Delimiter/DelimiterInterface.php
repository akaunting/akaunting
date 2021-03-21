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

interface DelimiterInterface
{
    public function canClose(): bool;

    public function canOpen(): bool;

    public function isActive(): bool;

    /**
     * @param bool $active
     *
     * @return void
     */
    public function setActive(bool $active);

    /**
     * @return string
     */
    public function getChar(): string;

    public function getIndex(): ?int;

    public function getNext(): ?DelimiterInterface;

    /**
     * @param DelimiterInterface|null $next
     *
     * @return void
     */
    public function setNext(?DelimiterInterface $next);

    public function getLength(): int;

    /**
     * @param int $length
     *
     * @return void
     */
    public function setLength(int $length);

    public function getOriginalLength(): int;

    public function getInlineNode(): AbstractStringContainer;

    public function getPrevious(): ?DelimiterInterface;

    /**
     * @param DelimiterInterface|null $previous
     *
     * @return mixed|void
     */
    public function setPrevious(?DelimiterInterface $previous);
}

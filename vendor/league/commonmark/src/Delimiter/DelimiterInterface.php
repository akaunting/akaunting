<?php

declare(strict_types=1);

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

use League\CommonMark\Node\Inline\AbstractStringContainer;

interface DelimiterInterface
{
    public function canClose(): bool;

    public function canOpen(): bool;

    public function isActive(): bool;

    public function setActive(bool $active): void;

    public function getChar(): string;

    public function getIndex(): ?int;

    public function getNext(): ?DelimiterInterface;

    public function setNext(?DelimiterInterface $next): void;

    public function getLength(): int;

    public function setLength(int $length): void;

    public function getOriginalLength(): int;

    public function getInlineNode(): AbstractStringContainer;

    public function getPrevious(): ?DelimiterInterface;

    public function setPrevious(?DelimiterInterface $previous): void;
}

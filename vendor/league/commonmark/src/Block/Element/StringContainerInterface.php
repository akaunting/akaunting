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

/**
 * Interface for a block which can contain line(s) of strings
 */
interface StringContainerInterface
{
    /**
     * @param string $line
     *
     * @return void
     */
    public function addLine(string $line);

    /**
     * @return string
     */
    public function getStringContent(): string;

    /**
     * @param ContextInterface $context
     * @param Cursor           $cursor
     *
     * @return void
     */
    public function handleRemainingContents(ContextInterface $context, Cursor $cursor);
}

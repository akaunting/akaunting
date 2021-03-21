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

namespace League\CommonMark\Inline\Element;

class Newline extends AbstractInline
{
    // Any changes to these constants should be reflected in .phpstorm.meta.php
    const HARDBREAK = 0;
    const SOFTBREAK = 1;

    /** @var int */
    protected $type;

    public function __construct(int $breakType = self::HARDBREAK)
    {
        $this->type = $breakType;
    }

    public function getType(): int
    {
        return $this->type;
    }
}

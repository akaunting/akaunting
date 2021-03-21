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

use League\CommonMark\Node\Node;

/**
 * @method children() AbstractInline[]
 */
abstract class AbstractInline extends Node
{
    /**
     * @var array<string, mixed>
     *
     * Used for storage of arbitrary data
     */
    public $data = [];

    public function isContainer(): bool
    {
        return false;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getData(string $key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
}

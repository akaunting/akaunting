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

class AbstractStringContainer extends AbstractInline
{
    /**
     * @var string
     */
    protected $content = '';

    /**
     * @param string               $contents
     * @param array<string, mixed> $data
     */
    public function __construct(string $contents = '', array $data = [])
    {
        $this->content = $contents;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $contents
     *
     * @return $this
     */
    public function setContent(string $contents)
    {
        $this->content = $contents;

        return $this;
    }
}

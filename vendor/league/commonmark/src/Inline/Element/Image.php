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

class Image extends AbstractWebResource
{
    public function __construct(string $url, ?string $label = null, ?string $title = null)
    {
        parent::__construct($url);

        if (!empty($label)) {
            $this->appendChild(new Text($label));
        }

        if (!empty($title)) {
            $this->data['title'] = $title;
        }
    }
}

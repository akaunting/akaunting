<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (http://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\SmartPunct;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

final class QuoteRenderer implements InlineRendererInterface
{
    /**
     * @param Quote                    $inline
     * @param ElementRendererInterface $htmlRenderer
     *
     * @return HtmlElement|string|null
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!$inline instanceof Quote) {
            throw new \InvalidArgumentException(sprintf('Expected an instance of "%s", got "%s" instead', Quote::class, get_class($inline)));
        }

        // Handles unpaired quotes which remain after processing delimiters
        if ($inline->getContent() === Quote::SINGLE_QUOTE) {
            // Render as an apostrophe
            return Quote::SINGLE_QUOTE_CLOSER;
        } elseif ($inline->getContent() === Quote::DOUBLE_QUOTE) {
            // Render as an opening quote
            return Quote::DOUBLE_QUOTE_OPENER;
        }

        return $inline->getContent();
    }
}

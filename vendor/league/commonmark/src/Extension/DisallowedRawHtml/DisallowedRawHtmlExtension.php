<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\DisallowedRawHtml;

use League\CommonMark\Block\Element\HtmlBlock;
use League\CommonMark\Block\Renderer\HtmlBlockRenderer;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Element\HtmlInline;
use League\CommonMark\Inline\Renderer\HtmlInlineRenderer;

final class DisallowedRawHtmlExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment->addBlockRenderer(HtmlBlock::class, new DisallowedRawHtmlBlockRenderer(new HtmlBlockRenderer()), 50);
        $environment->addInlineRenderer(HtmlInline::class, new DisallowedRawHtmlInlineRenderer(new HtmlInlineRenderer()), 50);
    }
}

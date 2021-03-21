<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\HeadingPermalink;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;

/**
 * Renders the HeadingPermalink elements
 */
final class HeadingPermalinkRenderer implements InlineRendererInterface, ConfigurationAwareInterface
{
    /** @deprecated */
    const DEFAULT_INNER_CONTENTS = '<svg class="heading-permalink-icon" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"></path></svg>';

    const DEFAULT_SYMBOL = '¶';

    /** @var ConfigurationInterface */
    private $config;

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->config = $configuration;
    }

    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!$inline instanceof HeadingPermalink) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . \get_class($inline));
        }

        $slug = $inline->getSlug();

        $idPrefix = (string) $this->config->get('heading_permalink/id_prefix', 'user-content');
        if ($idPrefix !== '') {
            $idPrefix .= '-';
        }

        $attrs = [
            'id'          => $idPrefix . $slug,
            'href'        => '#' . $slug,
            'name'        => $slug,
            'class'       => $this->config->get('heading_permalink/html_class', 'heading-permalink'),
            'aria-hidden' => 'true',
            'title'       => $this->config->get('heading_permalink/title', 'Permalink'),
        ];

        $innerContents = $this->config->get('heading_permalink/inner_contents');
        if ($innerContents !== null) {
            @trigger_error(sprintf('The %s config option is deprecated; use %s instead', 'inner_contents', 'symbol'), E_USER_DEPRECATED);

            return new HtmlElement('a', $attrs, $innerContents, false);
        }

        $symbol = $this->config->get('heading_permalink/symbol', self::DEFAULT_SYMBOL);

        return new HtmlElement('a', $attrs, \htmlspecialchars($symbol), false);
    }
}

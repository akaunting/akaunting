<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 * (c) Rezo Zero / Ambroise Maupate
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace League\CommonMark\Extension\Footnote\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Extension\Footnote\Node\Footnote;
use League\CommonMark\HtmlElement;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;

final class FootnoteRenderer implements BlockRendererInterface, ConfigurationAwareInterface
{
    /** @var ConfigurationInterface */
    private $config;

    /**
     * @param Footnote                 $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return HtmlElement
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (!($block instanceof Footnote)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        $attrs = $block->getData('attributes', []);
        $attrs['class'] = $attrs['class'] ?? $this->config->get('footnote/footnote_class', 'footnote');
        $attrs['id'] = $this->config->get('footnote/footnote_id_prefix', 'fn:') . \mb_strtolower($block->getReference()->getLabel());
        $attrs['role'] = 'doc-endnote';

        foreach ($block->getBackrefs() as $backref) {
            $block->lastChild()->appendChild($backref);
        }

        return new HtmlElement(
            'li',
            $attrs,
            $htmlRenderer->renderBlocks($block->children()),
            true
        );
    }

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->config = $configuration;
    }
}

<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\TableOfContents;

use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Exception\InvalidOptionException;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalink;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContentsPlaceholder;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\ConfigurationInterface;

final class TableOfContentsBuilder implements ConfigurationAwareInterface
{
    /**
     * @deprecated Use TableOfContentsGenerator::STYLE_BULLET instead
     */
    public const STYLE_BULLET = TableOfContentsGenerator::STYLE_BULLET;

    /**
     * @deprecated Use TableOfContentsGenerator::STYLE_ORDERED instead
     */
    public const STYLE_ORDERED = TableOfContentsGenerator::STYLE_ORDERED;

    /**
     * @deprecated Use TableOfContentsGenerator::NORMALIZE_DISABLED instead
     */
    public const NORMALIZE_DISABLED = TableOfContentsGenerator::NORMALIZE_DISABLED;

    /**
     * @deprecated Use TableOfContentsGenerator::NORMALIZE_RELATIVE instead
     */
    public const NORMALIZE_RELATIVE = TableOfContentsGenerator::NORMALIZE_RELATIVE;

    /**
     * @deprecated Use TableOfContentsGenerator::NORMALIZE_FLAT instead
     */
    public const NORMALIZE_FLAT = TableOfContentsGenerator::NORMALIZE_FLAT;

    public const POSITION_TOP = 'top';
    public const POSITION_BEFORE_HEADINGS = 'before-headings';
    public const POSITION_PLACEHOLDER = 'placeholder';

    /** @var ConfigurationInterface */
    private $config;

    public function onDocumentParsed(DocumentParsedEvent $event): void
    {
        $document = $event->getDocument();

        $generator = new TableOfContentsGenerator(
            $this->config->get('table_of_contents/style', TableOfContentsGenerator::STYLE_BULLET),
            $this->config->get('table_of_contents/normalize', TableOfContentsGenerator::NORMALIZE_RELATIVE),
            (int) $this->config->get('table_of_contents/min_heading_level', 1),
            (int) $this->config->get('table_of_contents/max_heading_level', 6)
        );

        $toc = $generator->generate($document);
        if ($toc === null) {
            // No linkable headers exist, so no TOC could be generated
            return;
        }

        // Add custom CSS class(es), if defined
        $class = $this->config->get('table_of_contents/html_class', 'table-of-contents');
        if (!empty($class)) {
            $toc->data['attributes']['class'] = $class;
        }

        // Add the TOC to the Document
        $position = $this->config->get('table_of_contents/position', self::POSITION_TOP);
        if ($position === self::POSITION_TOP) {
            $document->prependChild($toc);
        } elseif ($position === self::POSITION_BEFORE_HEADINGS) {
            $this->insertBeforeFirstLinkedHeading($document, $toc);
        } elseif ($position === self::POSITION_PLACEHOLDER) {
            $this->replacePlaceholders($document, $toc);
        } else {
            throw new InvalidOptionException(\sprintf('Invalid config option "%s" for "table_of_contents/position"', $position));
        }
    }

    private function insertBeforeFirstLinkedHeading(Document $document, TableOfContents $toc): void
    {
        $walker = $document->walker();
        while ($event = $walker->next()) {
            if ($event->isEntering() && ($node = $event->getNode()) instanceof HeadingPermalink && ($parent = $node->parent()) instanceof Heading) {
                $parent->insertBefore($toc);

                return;
            }
        }
    }

    private function replacePlaceholders(Document $document, TableOfContents $toc): void
    {
        $walker = $document->walker();
        while ($event = $walker->next()) {
            // Add the block once we find a placeholder (and we're about to leave it)
            if (!$event->getNode() instanceof TableOfContentsPlaceholder) {
                continue;
            }

            if ($event->isEntering()) {
                continue;
            }

            $event->getNode()->replaceWith(clone $toc);
        }
    }

    public function setConfiguration(ConfigurationInterface $config)
    {
        $this->config = $config;
    }
}

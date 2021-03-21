<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 * (c) 2015 Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace League\CommonMark\Extension\Attributes\Event;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\Attributes\Node\Attributes;
use League\CommonMark\Extension\Attributes\Node\AttributesInline;
use League\CommonMark\Extension\Attributes\Util\AttributesHelper;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Node\Node;

final class AttributesListener
{
    private const DIRECTION_PREFIX = 'prefix';
    private const DIRECTION_SUFFIX = 'suffix';

    public function processDocument(DocumentParsedEvent $event): void
    {
        $walker = $event->getDocument()->walker();
        while ($event = $walker->next()) {
            $node = $event->getNode();
            if (!$node instanceof AttributesInline && ($event->isEntering() || !$node instanceof Attributes)) {
                continue;
            }

            [$target, $direction] = self::findTargetAndDirection($node);

            if ($target instanceof AbstractBlock || $target instanceof AbstractInline) {
                $parent = $target->parent();
                if ($parent instanceof ListItem && $parent->parent() instanceof ListBlock && $parent->parent()->isTight()) {
                    $target = $parent;
                }

                if ($direction === self::DIRECTION_SUFFIX) {
                    $attributes = AttributesHelper::mergeAttributes($target, $node->getAttributes());
                } else {
                    $attributes = AttributesHelper::mergeAttributes($node->getAttributes(), $target);
                }

                $target->data['attributes'] = $attributes;
            }

            if ($node instanceof AbstractBlock && $node->endsWithBlankLine() && $node->next() && $node->previous()) {
                $previous = $node->previous();
                if ($previous instanceof AbstractBlock) {
                    $previous->setLastLineBlank(true);
                }
            }

            $node->detach();
        }
    }

    /**
     * @param Node $node
     *
     * @return array<Node|string|null>
     */
    private static function findTargetAndDirection(Node $node): array
    {
        $target = null;
        $direction = null;
        $previous = $next = $node;
        while (true) {
            $previous = self::getPrevious($previous);
            $next = self::getNext($next);

            if ($previous === null && $next === null) {
                if (!$node->parent() instanceof FencedCode) {
                    $target = $node->parent();
                    $direction = self::DIRECTION_SUFFIX;
                }

                break;
            }

            if ($node instanceof AttributesInline && ($previous === null || ($previous instanceof AbstractInline && $node->isBlock()))) {
                continue;
            }

            if ($previous !== null && !self::isAttributesNode($previous)) {
                $target = $previous;
                $direction = self::DIRECTION_SUFFIX;

                break;
            }

            if ($next !== null && !self::isAttributesNode($next)) {
                $target = $next;
                $direction = self::DIRECTION_PREFIX;

                break;
            }
        }

        return [$target, $direction];
    }

    private static function getPrevious(?Node $node = null): ?Node
    {
        $previous = $node instanceof Node ? $node->previous() : null;

        if ($previous instanceof AbstractBlock && $previous->endsWithBlankLine()) {
            $previous = null;
        }

        return $previous;
    }

    private static function getNext(?Node $node = null): ?Node
    {
        $next = $node instanceof Node ? $node->next() : null;

        if ($node instanceof AbstractBlock && $node->endsWithBlankLine()) {
            $next = null;
        }

        return $next;
    }

    private static function isAttributesNode(Node $node): bool
    {
        return $node instanceof Attributes || $node instanceof AttributesInline;
    }
}

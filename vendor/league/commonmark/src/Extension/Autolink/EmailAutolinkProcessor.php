<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\Autolink;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Element\Text;

final class EmailAutolinkProcessor
{
    const REGEX = '/([A-Za-z0-9.\-_+]+@[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_.]+)/';

    /**
     * @param DocumentParsedEvent $e
     *
     * @return void
     */
    public function __invoke(DocumentParsedEvent $e)
    {
        $walker = $e->getDocument()->walker();

        while ($event = $walker->next()) {
            $node = $event->getNode();
            if ($node instanceof Text && !($node->parent() instanceof Link)) {
                self::processAutolinks($node);
            }
        }
    }

    private static function processAutolinks(Text $node): void
    {
        $contents = \preg_split(self::REGEX, $node->getContent(), -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($contents === false || \count($contents) === 1) {
            return;
        }

        $leftovers = '';
        foreach ($contents as $i => $content) {
            if ($i % 2 === 0) {
                $text = $leftovers . $content;
                if ($text !== '') {
                    $node->insertBefore(new Text($leftovers . $content));
                }

                $leftovers = '';
                continue;
            }

            // Does the URL end with punctuation that should be stripped?
            if (\substr($content, -1) === '.') {
                // Add the punctuation later
                $content = \substr($content, 0, -1);
                $leftovers = '.';
            }

            // The last character cannot be - or _
            if (\in_array(\substr($content, -1), ['-', '_'])) {
                $node->insertBefore(new Text($content . $leftovers));
                $leftovers = '';
                continue;
            }

            $node->insertBefore(new Link('mailto:' . $content, $content));
        }

        $node->detach();
    }
}

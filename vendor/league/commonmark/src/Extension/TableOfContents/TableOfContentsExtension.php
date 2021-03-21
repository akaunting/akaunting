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

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContentsPlaceholder;

final class TableOfContentsExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, [new TableOfContentsBuilder(), 'onDocumentParsed'], -150);

        if ($environment->getConfig('table_of_contents/position') === TableOfContentsBuilder::POSITION_PLACEHOLDER) {
            $environment->addBlockParser(new TableOfContentsPlaceholderParser(), 200);
            // If a placeholder cannot be replaced with a TOC element this renderer will ensure the parser won't error out
            $environment->addBlockRenderer(TableOfContentsPlaceholder::class, new TableOfContentsPlaceholderRenderer());
        }
    }
}

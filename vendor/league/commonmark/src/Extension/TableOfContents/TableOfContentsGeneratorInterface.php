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
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents;

interface TableOfContentsGeneratorInterface
{
    public function generate(Document $document): ?TableOfContents;
}

// Trigger autoload without causing a deprecated error
\class_exists(TableOfContents::class);

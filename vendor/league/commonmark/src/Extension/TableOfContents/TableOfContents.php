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

use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents as NewTableOfContents;

if (!class_exists(NewTableOfContents::class)) {
    @trigger_error(sprintf('TableOfContents has moved to a new namespace; use %s instead', NewTableOfContents::class), \E_USER_DEPRECATED);
}

\class_alias(NewTableOfContents::class, TableOfContents::class);

if (false) {
    /**
     * @deprecated This class has moved to the Node sub-namespace; use that instead
     */
    final class TableOfContents extends ListBlock
    {
    }
}

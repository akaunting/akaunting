<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\TableOfContents\Node;

use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Extension\TableOfContents\TableOfContents as DeprecatedTableOfContents;

final class TableOfContents extends ListBlock
{
}

\class_exists(DeprecatedTableOfContents::class);

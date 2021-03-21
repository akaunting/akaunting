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

namespace League\CommonMark\Extension\Footnote\Parser;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Extension\Footnote\Node\Footnote;
use League\CommonMark\Reference\Reference;
use League\CommonMark\Util\RegexHelper;

final class FootnoteParser implements BlockParserInterface
{
    public function parse(ContextInterface $context, Cursor $cursor): bool
    {
        if ($cursor->isIndented()) {
            return false;
        }

        $match = RegexHelper::matchAll(
            '/^\[\^([^\n^\]]+)\]\:\s/',
            $cursor->getLine(),
            $cursor->getNextNonSpacePosition()
        );

        if (!$match) {
            return false;
        }

        $cursor->advanceToNextNonSpaceOrTab();
        $cursor->advanceBy(\strlen($match[0]));
        $str = $cursor->getRemainder();
        \preg_replace('/^\[\^([^\n^\]]+)\]\:\s/', '', $str);

        if (\preg_match('/^\[\^([^\n^\]]+)\]\:\s/', $match[0], $matches) > 0) {
            $context->addBlock($this->createFootnote($matches[1]));
            $context->setBlocksParsed(true);

            return true;
        }

        return false;
    }

    private function createFootnote(string $label): Footnote
    {
        return new Footnote(
            new Reference($label, $label, $label)
        );
    }
}

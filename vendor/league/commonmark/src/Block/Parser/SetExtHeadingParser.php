<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Block\Parser;

use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;
use League\CommonMark\Reference\ReferenceParser;
use League\CommonMark\Util\RegexHelper;

final class SetExtHeadingParser implements BlockParserInterface
{
    public function parse(ContextInterface $context, Cursor $cursor): bool
    {
        if ($cursor->isIndented()) {
            return false;
        }

        if (!($context->getContainer() instanceof Paragraph)) {
            return false;
        }

        $match = RegexHelper::matchAll('/^(?:=+|-+)[ \t]*$/', $cursor->getLine(), $cursor->getNextNonSpacePosition());
        if ($match === null) {
            return false;
        }

        $level = $match[0][0] === '=' ? 1 : 2;
        $strings = $context->getContainer()->getStrings();

        $strings = $this->resolveReferenceLinkDefinitions($strings, $context->getReferenceParser());
        if (empty($strings)) {
            return false;
        }

        $context->replaceContainerBlock(new Heading($level, $strings));

        return true;
    }

    /**
     * Resolve reference link definition
     *
     * @see https://github.com/commonmark/commonmark.js/commit/993bbe335931af847460effa99b2411eb643577d
     *
     * @param string[]        $strings
     * @param ReferenceParser $referenceParser
     *
     * @return string[]
     */
    private function resolveReferenceLinkDefinitions(array $strings, ReferenceParser $referenceParser): array
    {
        foreach ($strings as &$string) {
            $cursor = new Cursor($string);
            while ($cursor->getCharacter() === '[' && $referenceParser->parse($cursor)) {
                $string = $cursor->getRemainder();
            }

            if ($string !== '') {
                break;
            }
        }

        return \array_filter($strings, function ($s) {
            return $s !== '';
        });
    }
}

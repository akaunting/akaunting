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

namespace League\CommonMark\Inline\Parser;

use League\CommonMark\Cursor;
use League\CommonMark\Delimiter\DelimiterInterface;
use League\CommonMark\EnvironmentAwareInterface;
use League\CommonMark\EnvironmentInterface;
use League\CommonMark\Inline\AdjacentTextMerger;
use League\CommonMark\Inline\Element\AbstractWebResource;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Reference\ReferenceInterface;
use League\CommonMark\Reference\ReferenceMapInterface;
use League\CommonMark\Util\LinkParserHelper;
use League\CommonMark\Util\RegexHelper;

final class CloseBracketParser implements InlineParserInterface, EnvironmentAwareInterface
{
    /**
     * @var EnvironmentInterface
     */
    private $environment;

    public function getCharacters(): array
    {
        return [']'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        // Look through stack of delimiters for a [ or !
        $opener = $inlineContext->getDelimiterStack()->searchByCharacter(['[', '!']);
        if ($opener === null) {
            return false;
        }

        if (!$opener->isActive()) {
            // no matched opener; remove from emphasis stack
            $inlineContext->getDelimiterStack()->removeDelimiter($opener);

            return false;
        }

        $cursor = $inlineContext->getCursor();

        $startPos = $cursor->getPosition();
        $previousState = $cursor->saveState();

        $cursor->advanceBy(1);

        // Check to see if we have a link/image
        if (!($link = $this->tryParseLink($cursor, $inlineContext->getReferenceMap(), $opener, $startPos))) {
            // No match
            $inlineContext->getDelimiterStack()->removeDelimiter($opener); // Remove this opener from stack
            $cursor->restoreState($previousState);

            return false;
        }

        $isImage = $opener->getChar() === '!';

        $inline = $this->createInline($link['url'], $link['title'], $isImage);
        $opener->getInlineNode()->replaceWith($inline);
        while (($label = $inline->next()) !== null) {
            $inline->appendChild($label);
        }

        // Process delimiters such as emphasis inside link/image
        $delimiterStack = $inlineContext->getDelimiterStack();
        $stackBottom = $opener->getPrevious();
        $delimiterStack->processDelimiters($stackBottom, $this->environment->getDelimiterProcessors());
        $delimiterStack->removeAll($stackBottom);

        // Merge any adjacent Text nodes together
        AdjacentTextMerger::mergeChildNodes($inline);

        // processEmphasis will remove this and later delimiters.
        // Now, for a link, we also remove earlier link openers (no links in links)
        if (!$isImage) {
            $inlineContext->getDelimiterStack()->removeEarlierMatches('[');
        }

        return true;
    }

    public function setEnvironment(EnvironmentInterface $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param Cursor                $cursor
     * @param ReferenceMapInterface $referenceMap
     * @param DelimiterInterface    $opener
     * @param int                   $startPos
     *
     * @return array<string, string>|false
     */
    private function tryParseLink(Cursor $cursor, ReferenceMapInterface $referenceMap, DelimiterInterface $opener, int $startPos)
    {
        // Check to see if we have a link/image
        // Inline link?
        if ($result = $this->tryParseInlineLinkAndTitle($cursor)) {
            return $result;
        }

        if ($link = $this->tryParseReference($cursor, $referenceMap, $opener, $startPos)) {
            return ['url' => $link->getDestination(), 'title' => $link->getTitle()];
        }

        return false;
    }

    /**
     * @param Cursor $cursor
     *
     * @return array<string, string>|false
     */
    private function tryParseInlineLinkAndTitle(Cursor $cursor)
    {
        if ($cursor->getCharacter() !== '(') {
            return false;
        }

        $previousState = $cursor->saveState();

        $cursor->advanceBy(1);
        $cursor->advanceToNextNonSpaceOrNewline();
        if (($dest = LinkParserHelper::parseLinkDestination($cursor)) === null) {
            $cursor->restoreState($previousState);

            return false;
        }

        $cursor->advanceToNextNonSpaceOrNewline();

        $title = '';
        // make sure there's a space before the title:
        if (\preg_match(RegexHelper::REGEX_WHITESPACE_CHAR, $cursor->peek(-1))) {
            $title = LinkParserHelper::parseLinkTitle($cursor) ?? '';
        }

        $cursor->advanceToNextNonSpaceOrNewline();

        if ($cursor->getCharacter() !== ')') {
            $cursor->restoreState($previousState);

            return false;
        }

        $cursor->advanceBy(1);

        return ['url' => $dest, 'title' => $title];
    }

    private function tryParseReference(Cursor $cursor, ReferenceMapInterface $referenceMap, DelimiterInterface $opener, int $startPos): ?ReferenceInterface
    {
        if ($opener->getIndex() === null) {
            return null;
        }

        $savePos = $cursor->saveState();
        $beforeLabel = $cursor->getPosition();
        $n = LinkParserHelper::parseLinkLabel($cursor);
        if ($n === 0 || $n === 2) {
            $start = $opener->getIndex();
            $length = $startPos - $opener->getIndex();
        } else {
            $start = $beforeLabel + 1;
            $length = $n - 2;
        }

        $referenceLabel = $cursor->getSubstring($start, $length);

        if ($n === 0) {
            // If shortcut reference link, rewind before spaces we skipped
            $cursor->restoreState($savePos);
        }

        return $referenceMap->getReference($referenceLabel);
    }

    private function createInline(string $url, string $title, bool $isImage): AbstractWebResource
    {
        if ($isImage) {
            return new Image($url, null, $title);
        }

        return new Link($url, null, $title);
    }
}

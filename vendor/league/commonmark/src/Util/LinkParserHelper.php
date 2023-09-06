<?php

declare(strict_types=1);

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

namespace League\CommonMark\Util;

use League\CommonMark\Parser\Cursor;

/**
 * @psalm-immutable
 */
final class LinkParserHelper
{
    /**
     * Attempt to parse link destination
     *
     * @return string|null The string, or null if no match
     */
    public static function parseLinkDestination(Cursor $cursor): ?string
    {
        if ($res = $cursor->match(RegexHelper::REGEX_LINK_DESTINATION_BRACES)) {
            // Chop off surrounding <..>:
            return UrlEncoder::unescapeAndEncode(
                RegexHelper::unescape(\substr($res, 1, -1))
            );
        }

        if ($cursor->getCurrentCharacter() === '<') {
            return null;
        }

        $destination = self::manuallyParseLinkDestination($cursor);
        if ($destination === null) {
            return null;
        }

        return UrlEncoder::unescapeAndEncode(
            RegexHelper::unescape($destination)
        );
    }

    public static function parseLinkLabel(Cursor $cursor): int
    {
        $match = $cursor->match('/^\[(?:[^\\\\\[\]]|\\\\.){0,1000}\]/');
        if ($match === null) {
            return 0;
        }

        $length = \mb_strlen($match, 'UTF-8');

        if ($length > 1001) {
            return 0;
        }

        return $length;
    }

    public static function parsePartialLinkLabel(Cursor $cursor): ?string
    {
        return $cursor->match('/^(?:[^\\\\\[\]]+|\\\\.?)*/');
    }

    /**
     * Attempt to parse link title (sans quotes)
     *
     * @return string|null The string, or null if no match
     */
    public static function parseLinkTitle(Cursor $cursor): ?string
    {
        if ($title = $cursor->match('/' . RegexHelper::PARTIAL_LINK_TITLE . '/')) {
            // Chop off quotes from title and unescape
            return RegexHelper::unescape(\substr($title, 1, -1));
        }

        return null;
    }

    public static function parsePartialLinkTitle(Cursor $cursor, string $endDelimiter): ?string
    {
        $endDelimiter = \preg_quote($endDelimiter, '/');
        $regex        = \sprintf('/(%s|[^%s\x00])*(?:%s)?/', RegexHelper::PARTIAL_ESCAPED_CHAR, $endDelimiter, $endDelimiter);
        if (($partialTitle = $cursor->match($regex)) === null) {
            return null;
        }

        return RegexHelper::unescape($partialTitle);
    }

    private static function manuallyParseLinkDestination(Cursor $cursor): ?string
    {
        $oldPosition = $cursor->getPosition();
        $oldState    = $cursor->saveState();

        $openParens = 0;
        while (($c = $cursor->getCurrentCharacter()) !== null) {
            if ($c === '\\' && ($peek = $cursor->peek()) !== null && RegexHelper::isEscapable($peek)) {
                $cursor->advanceBy(2);
            } elseif ($c === '(') {
                $cursor->advanceBy(1);
                $openParens++;
            } elseif ($c === ')') {
                if ($openParens < 1) {
                    break;
                }

                $cursor->advanceBy(1);
                $openParens--;
            } elseif (\preg_match(RegexHelper::REGEX_WHITESPACE_CHAR, $c)) {
                break;
            } else {
                $cursor->advanceBy(1);
            }
        }

        if ($openParens !== 0) {
            return null;
        }

        if ($cursor->getPosition() === $oldPosition && (! isset($c) || $c !== ')')) {
            return null;
        }

        $newPos = $cursor->getPosition();
        $cursor->restoreState($oldState);

        $cursor->advanceBy($newPos - $cursor->getPosition());

        return $cursor->getPreviousText();
    }
}

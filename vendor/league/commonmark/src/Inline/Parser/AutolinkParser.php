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

use League\CommonMark\Inline\Element\Link;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Util\UrlEncoder;

final class AutolinkParser implements InlineParserInterface
{
    const EMAIL_REGEX = '/^<([a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*)>/';
    const OTHER_LINK_REGEX = '/^<[A-Za-z][A-Za-z0-9.+-]{1,31}:[^<>\x00-\x20]*>/i';

    public function getCharacters(): array
    {
        return ['<'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        if ($m = $cursor->match(self::EMAIL_REGEX)) {
            $email = \substr($m, 1, -1);
            $inlineContext->getContainer()->appendChild(new Link('mailto:' . UrlEncoder::unescapeAndEncode($email), $email));

            return true;
        } elseif ($m = $cursor->match(self::OTHER_LINK_REGEX)) {
            $dest = \substr($m, 1, -1);
            $inlineContext->getContainer()->appendChild(new Link(UrlEncoder::unescapeAndEncode($dest), $dest));

            return true;
        }

        return false;
    }
}

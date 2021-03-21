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

use League\CommonMark\Extension\Mention\MentionParser;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;

@trigger_error(sprintf('%s is deprecated; use %s instead', InlineMentionParser::class, MentionParser::class), E_USER_DEPRECATED);

/**
 * @deprecated Use MentionParser instead
 */
final class InlineMentionParser implements InlineParserInterface
{
    /** @var string */
    private $linkPattern;

    /** @var string */
    private $handleRegex;

    /**
     * @param string $linkPattern
     * @param string $handleRegex
     */
    public function __construct($linkPattern, $handleRegex = '/^[A-Za-z0-9_]+(?!\w)/')
    {
        $this->linkPattern = $linkPattern;
        $this->handleRegex = $handleRegex;
    }

    public function getCharacters(): array
    {
        return ['@'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();

        // The @ symbol must not have any other characters immediately prior
        $previousChar = $cursor->peek(-1);
        if ($previousChar !== null && $previousChar !== ' ') {
            // peek() doesn't modify the cursor, so no need to restore state first
            return false;
        }

        // Save the cursor state in case we need to rewind and bail
        $previousState = $cursor->saveState();

        // Advance past the @ symbol to keep parsing simpler
        $cursor->advance();

        // Parse the handle
        $handle = $cursor->match($this->handleRegex);
        if (empty($handle)) {
            // Regex failed to match; this isn't a valid Twitter handle
            $cursor->restoreState($previousState);

            return false;
        }

        $url = \sprintf($this->linkPattern, $handle);

        $inlineContext->getContainer()->appendChild(new Link($url, '@' . $handle));

        return true;
    }

    /**
     * @return InlineMentionParser
     */
    public static function createTwitterHandleParser()
    {
        return new self('https://twitter.com/%s', '/^[A-Za-z0-9_]{1,15}(?!\w)/');
    }

    /**
     * @return InlineMentionParser
     */
    public static function createGithubHandleParser()
    {
        // RegEx adapted from https://github.com/shinnn/github-username-regex/blob/master/index.js
        return new self('https://www.github.com/%s', '/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}(?!\w)/');
    }
}

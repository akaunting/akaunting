<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\Mention;

use League\CommonMark\Extension\Mention\Generator\CallbackGenerator;
use League\CommonMark\Extension\Mention\Generator\MentionGeneratorInterface;
use League\CommonMark\Extension\Mention\Generator\StringTemplateLinkGenerator;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;

final class MentionParser implements InlineParserInterface
{
    /** @var string */
    private $symbol;

    /** @var string */
    private $mentionRegex;

    /** @var MentionGeneratorInterface */
    private $mentionGenerator;

    public function __construct(string $symbol, string $mentionRegex, MentionGeneratorInterface $mentionGenerator)
    {
        $this->symbol = $symbol;
        $this->mentionRegex = $mentionRegex;
        $this->mentionGenerator = $mentionGenerator;
    }

    public function getCharacters(): array
    {
        return [$this->symbol];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();

        // The symbol must not have any other characters immediately prior
        $previousChar = $cursor->peek(-1);
        if ($previousChar !== null && \preg_match('/\w/', $previousChar)) {
            // peek() doesn't modify the cursor, so no need to restore state first
            return false;
        }

        // Save the cursor state in case we need to rewind and bail
        $previousState = $cursor->saveState();

        // Advance past the symbol to keep parsing simpler
        $cursor->advance();

        // Parse the mention match value
        $identifier = $cursor->match($this->mentionRegex);
        if ($identifier === null) {
            // Regex failed to match; this isn't a valid mention
            $cursor->restoreState($previousState);

            return false;
        }

        $mention = $this->mentionGenerator->generateMention(new Mention($this->symbol, $identifier));

        if ($mention === null) {
            $cursor->restoreState($previousState);

            return false;
        }

        $inlineContext->getContainer()->appendChild($mention);

        return true;
    }

    public static function createWithStringTemplate(string $symbol, string $mentionRegex, string $urlTemplate): MentionParser
    {
        return new self($symbol, $mentionRegex, new StringTemplateLinkGenerator($urlTemplate));
    }

    public static function createWithCallback(string $symbol, string $mentionRegex, callable $callback): MentionParser
    {
        return new self($symbol, $mentionRegex, new CallbackGenerator($callback));
    }
}

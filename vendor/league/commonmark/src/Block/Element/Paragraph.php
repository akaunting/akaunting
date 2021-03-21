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

namespace League\CommonMark\Block\Element;

use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

class Paragraph extends AbstractStringContainerBlock implements InlineContainerInterface
{
    public function canContain(AbstractBlock $block): bool
    {
        return false;
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        if ($cursor->isBlank()) {
            $this->lastLineBlank = true;

            return false;
        }

        return true;
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        $this->finalStringContents = \preg_replace('/^  */m', '', \implode("\n", $this->getStrings()));

        // Short-circuit
        if ($this->finalStringContents === '' || $this->finalStringContents[0] !== '[') {
            return;
        }

        $cursor = new Cursor($this->finalStringContents);

        $referenceFound = $this->parseReferences($context, $cursor);

        $this->finalStringContents = $cursor->getRemainder();

        if ($referenceFound && $cursor->isAtEnd()) {
            $this->detach();
        }
    }

    /**
     * @param ContextInterface $context
     * @param Cursor           $cursor
     *
     * @return bool
     */
    protected function parseReferences(ContextInterface $context, Cursor $cursor)
    {
        $referenceFound = false;
        while ($cursor->getCharacter() === '[' && $context->getReferenceParser()->parse($cursor)) {
            $this->finalStringContents = $cursor->getRemainder();
            $referenceFound = true;
        }

        return $referenceFound;
    }

    public function handleRemainingContents(ContextInterface $context, Cursor $cursor)
    {
        $cursor->advanceToNextNonSpaceOrTab();

        /** @var self $tip */
        $tip = $context->getTip();
        $tip->addLine($cursor->getRemainder());
    }

    /**
     * @return string[]
     */
    public function getStrings(): array
    {
        return $this->strings->toArray();
    }
}

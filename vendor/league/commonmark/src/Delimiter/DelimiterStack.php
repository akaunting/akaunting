<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * Additional emphasis processing code based on commonmark-java (https://github.com/atlassian/commonmark-java)
 *  - (c) Atlassian Pty Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Delimiter;

use League\CommonMark\Delimiter\Processor\DelimiterProcessorCollection;
use League\CommonMark\Inline\AdjacentTextMerger;

final class DelimiterStack
{
    /**
     * @var DelimiterInterface|null
     */
    private $top;

    /**
     * @param DelimiterInterface $newDelimiter
     *
     * @return void
     */
    public function push(DelimiterInterface $newDelimiter)
    {
        $newDelimiter->setPrevious($this->top);

        if ($this->top !== null) {
            $this->top->setNext($newDelimiter);
        }

        $this->top = $newDelimiter;
    }

    private function findEarliest(DelimiterInterface $stackBottom = null): ?DelimiterInterface
    {
        $delimiter = $this->top;
        while ($delimiter !== null && $delimiter->getPrevious() !== $stackBottom) {
            $delimiter = $delimiter->getPrevious();
        }

        return $delimiter;
    }

    /**
     * @param DelimiterInterface $delimiter
     *
     * @return void
     */
    public function removeDelimiter(DelimiterInterface $delimiter)
    {
        if ($delimiter->getPrevious() !== null) {
            $delimiter->getPrevious()->setNext($delimiter->getNext());
        }

        if ($delimiter->getNext() === null) {
            // top of stack
            $this->top = $delimiter->getPrevious();
        } else {
            $delimiter->getNext()->setPrevious($delimiter->getPrevious());
        }
    }

    private function removeDelimiterAndNode(DelimiterInterface $delimiter): void
    {
        $delimiter->getInlineNode()->detach();
        $this->removeDelimiter($delimiter);
    }

    private function removeDelimitersBetween(DelimiterInterface $opener, DelimiterInterface $closer): void
    {
        $delimiter = $closer->getPrevious();
        while ($delimiter !== null && $delimiter !== $opener) {
            $previous = $delimiter->getPrevious();
            $this->removeDelimiter($delimiter);
            $delimiter = $previous;
        }
    }

    /**
     * @param DelimiterInterface|null $stackBottom
     *
     * @return void
     */
    public function removeAll(DelimiterInterface $stackBottom = null)
    {
        while ($this->top && $this->top !== $stackBottom) {
            $this->removeDelimiter($this->top);
        }
    }

    /**
     * @param string $character
     *
     * @return void
     */
    public function removeEarlierMatches(string $character)
    {
        $opener = $this->top;
        while ($opener !== null) {
            if ($opener->getChar() === $character) {
                $opener->setActive(false);
            }

            $opener = $opener->getPrevious();
        }
    }

    /**
     * @param string|string[] $characters
     *
     * @return DelimiterInterface|null
     */
    public function searchByCharacter($characters): ?DelimiterInterface
    {
        if (!\is_array($characters)) {
            $characters = [$characters];
        }

        $opener = $this->top;
        while ($opener !== null) {
            if (\in_array($opener->getChar(), $characters)) {
                break;
            }
            $opener = $opener->getPrevious();
        }

        return $opener;
    }

    /**
     * @param DelimiterInterface|null      $stackBottom
     * @param DelimiterProcessorCollection $processors
     *
     * @return void
     */
    public function processDelimiters(?DelimiterInterface $stackBottom, DelimiterProcessorCollection $processors)
    {
        $openersBottom = [];

        // Find first closer above stackBottom
        $closer = $this->findEarliest($stackBottom);

        // Move forward, looking for closers, and handling each
        while ($closer !== null) {
            $delimiterChar = $closer->getChar();

            $delimiterProcessor = $processors->getDelimiterProcessor($delimiterChar);
            if (!$closer->canClose() || $delimiterProcessor === null) {
                $closer = $closer->getNext();
                continue;
            }

            $openingDelimiterChar = $delimiterProcessor->getOpeningCharacter();

            $useDelims = 0;
            $openerFound = false;
            $potentialOpenerFound = false;
            $opener = $closer->getPrevious();
            while ($opener !== null && $opener !== $stackBottom && $opener !== ($openersBottom[$delimiterChar] ?? null)) {
                if ($opener->canOpen() && $opener->getChar() === $openingDelimiterChar) {
                    $potentialOpenerFound = true;
                    $useDelims = $delimiterProcessor->getDelimiterUse($opener, $closer);
                    if ($useDelims > 0) {
                        $openerFound = true;
                        break;
                    }
                }
                $opener = $opener->getPrevious();
            }

            if (!$openerFound) {
                if (!$potentialOpenerFound) {
                    // Only do this when we didn't even have a potential
                    // opener (one that matches the character and can open).
                    // If an opener was rejected because of the number of
                    // delimiters (e.g. because of the "multiple of 3"
                    // Set lower bound for future searches for openersrule),
                    // we want to consider it next time because the number
                    // of delimiters can change as we continue processing.
                    $openersBottom[$delimiterChar] = $closer->getPrevious();
                    if (!$closer->canOpen()) {
                        // We can remove a closer that can't be an opener,
                        // once we've seen there's no matching opener.
                        $this->removeDelimiter($closer);
                    }
                }
                $closer = $closer->getNext();
                continue;
            }

            $openerNode = $opener->getInlineNode();
            $closerNode = $closer->getInlineNode();

            // Remove number of used delimiters from stack and inline nodes.
            $opener->setLength($opener->getLength() - $useDelims);
            $closer->setLength($closer->getLength() - $useDelims);

            $openerNode->setContent(\substr($openerNode->getContent(), 0, -$useDelims));
            $closerNode->setContent(\substr($closerNode->getContent(), 0, -$useDelims));

            $this->removeDelimitersBetween($opener, $closer);
            // The delimiter processor can re-parent the nodes between opener and closer,
            // so make sure they're contiguous already. Exclusive because we want to keep opener/closer themselves.
            AdjacentTextMerger::mergeTextNodesBetweenExclusive($openerNode, $closerNode);
            $delimiterProcessor->process($openerNode, $closerNode, $useDelims);

            // No delimiter characters left to process, so we can remove delimiter and the now empty node.
            if ($opener->getLength() === 0) {
                $this->removeDelimiterAndNode($opener);
            }

            if ($closer->getLength() === 0) {
                $next = $closer->getNext();
                $this->removeDelimiterAndNode($closer);
                $closer = $next;
            }
        }

        // Remove all delimiters
        $this->removeAll($stackBottom);
    }
}

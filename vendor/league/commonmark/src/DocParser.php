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

namespace League\CommonMark;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\AbstractStringContainerBlock;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\Block\Element\StringContainerInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Event\DocumentPreParsedEvent;
use League\CommonMark\Input\MarkdownInput;

final class DocParser implements DocParserInterface
{
    /**
     * @var EnvironmentInterface
     */
    private $environment;

    /**
     * @var InlineParserEngine
     */
    private $inlineParserEngine;

    /**
     * @var int|float
     */
    private $maxNestingLevel;

    /**
     * @param EnvironmentInterface $environment
     */
    public function __construct(EnvironmentInterface $environment)
    {
        $this->environment = $environment;
        $this->inlineParserEngine = new InlineParserEngine($environment);
        $this->maxNestingLevel = $environment->getConfig('max_nesting_level', \INF);
    }

    /**
     * @param string $input
     *
     * @throws \RuntimeException
     *
     * @return Document
     */
    public function parse(string $input): Document
    {
        $document = new Document();

        $preParsedEvent = new DocumentPreParsedEvent($document, new MarkdownInput($input));
        $this->environment->dispatch($preParsedEvent);
        $markdown = $preParsedEvent->getMarkdown();

        $context = new Context($document, $this->environment);

        foreach ($markdown->getLines() as $line) {
            $context->setNextLine($line);
            $this->incorporateLine($context);
        }

        $lineCount = $markdown->getLineCount();
        while ($tip = $context->getTip()) {
            $tip->finalize($context, $lineCount);
        }

        $this->processInlines($context);

        $this->environment->dispatch(new DocumentParsedEvent($document));

        return $document;
    }

    private function incorporateLine(ContextInterface $context): void
    {
        $context->getBlockCloser()->resetTip();
        $context->setBlocksParsed(false);

        $cursor = new Cursor($context->getLine());

        $this->resetContainer($context, $cursor);
        $context->getBlockCloser()->setLastMatchedContainer($context->getContainer());

        $this->parseBlocks($context, $cursor);

        // What remains at the offset is a text line.  Add the text to the appropriate container.
        // First check for a lazy paragraph continuation:
        if ($this->handleLazyParagraphContinuation($context, $cursor)) {
            return;
        }

        // not a lazy continuation
        // finalize any blocks not matched
        $context->getBlockCloser()->closeUnmatchedBlocks();

        // Determine whether the last line is blank, updating parents as needed
        $this->setAndPropagateLastLineBlank($context, $cursor);

        // Handle any remaining cursor contents
        if ($context->getContainer() instanceof StringContainerInterface) {
            $context->getContainer()->handleRemainingContents($context, $cursor);
        } elseif (!$cursor->isBlank()) {
            // Create paragraph container for line
            $p = new Paragraph();
            $context->addBlock($p);
            $cursor->advanceToNextNonSpaceOrTab();
            $p->addLine($cursor->getRemainder());
        }
    }

    private function processInlines(ContextInterface $context): void
    {
        $walker = $context->getDocument()->walker();

        while ($event = $walker->next()) {
            if (!$event->isEntering()) {
                continue;
            }

            $node = $event->getNode();
            if ($node instanceof AbstractStringContainerBlock) {
                $this->inlineParserEngine->parse($node, $context->getDocument()->getReferenceMap());
            }
        }
    }

    /**
     * Sets the container to the last open child (or its parent)
     *
     * @param ContextInterface $context
     * @param Cursor           $cursor
     */
    private function resetContainer(ContextInterface $context, Cursor $cursor): void
    {
        $container = $context->getDocument();

        while ($lastChild = $container->lastChild()) {
            if (!($lastChild instanceof AbstractBlock)) {
                break;
            }

            if (!$lastChild->isOpen()) {
                break;
            }

            $container = $lastChild;
            if (!$container->matchesNextLine($cursor)) {
                $container = $container->parent(); // back up to the last matching block
                break;
            }
        }

        $context->setContainer($container);
    }

    /**
     * Parse blocks
     *
     * @param ContextInterface $context
     * @param Cursor           $cursor
     */
    private function parseBlocks(ContextInterface $context, Cursor $cursor): void
    {
        while (!$context->getContainer()->isCode() && !$context->getBlocksParsed()) {
            $parsed = false;
            foreach ($this->environment->getBlockParsers() as $parser) {
                if ($parser->parse($context, $cursor)) {
                    $parsed = true;
                    break;
                }
            }

            if (!$parsed || $context->getContainer() instanceof StringContainerInterface || (($tip = $context->getTip()) && $tip->getDepth() >= $this->maxNestingLevel)) {
                $context->setBlocksParsed(true);
                break;
            }
        }
    }

    private function handleLazyParagraphContinuation(ContextInterface $context, Cursor $cursor): bool
    {
        $tip = $context->getTip();

        if ($tip instanceof Paragraph &&
            !$context->getBlockCloser()->areAllClosed() &&
            !$cursor->isBlank() &&
            \count($tip->getStrings()) > 0) {

            // lazy paragraph continuation
            $tip->addLine($cursor->getRemainder());

            return true;
        }

        return false;
    }

    private function setAndPropagateLastLineBlank(ContextInterface $context, Cursor $cursor): void
    {
        $container = $context->getContainer();

        if ($cursor->isBlank() && $lastChild = $container->lastChild()) {
            if ($lastChild instanceof AbstractBlock) {
                $lastChild->setLastLineBlank(true);
            }
        }

        $lastLineBlank = $container->shouldLastLineBeBlank($cursor, $context->getLineNumber());

        // Propagate lastLineBlank up through parents:
        while ($container instanceof AbstractBlock && $container->endsWithBlankLine() !== $lastLineBlank) {
            $container->setLastLineBlank($lastLineBlank);
            $container = $container->parent();
        }
    }
}

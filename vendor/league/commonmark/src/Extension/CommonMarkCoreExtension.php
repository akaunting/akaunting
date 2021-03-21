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

namespace League\CommonMark\Extension;

use League\CommonMark\Block\Element as BlockElement;
use League\CommonMark\Block\Parser as BlockParser;
use League\CommonMark\Block\Renderer as BlockRenderer;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Delimiter\Processor\EmphasisDelimiterProcessor;
use League\CommonMark\Inline\Element as InlineElement;
use League\CommonMark\Inline\Parser as InlineParser;
use League\CommonMark\Inline\Renderer as InlineRenderer;

final class CommonMarkCoreExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment
            ->addBlockParser(new BlockParser\BlockQuoteParser(),      70)
            ->addBlockParser(new BlockParser\ATXHeadingParser(),      60)
            ->addBlockParser(new BlockParser\FencedCodeParser(),      50)
            ->addBlockParser(new BlockParser\HtmlBlockParser(),       40)
            ->addBlockParser(new BlockParser\SetExtHeadingParser(),   30)
            ->addBlockParser(new BlockParser\ThematicBreakParser(),   20)
            ->addBlockParser(new BlockParser\ListParser(),            10)
            ->addBlockParser(new BlockParser\IndentedCodeParser(),  -100)
            ->addBlockParser(new BlockParser\LazyParagraphParser(), -200)

            ->addInlineParser(new InlineParser\NewlineParser(),     200)
            ->addInlineParser(new InlineParser\BacktickParser(),    150)
            ->addInlineParser(new InlineParser\EscapableParser(),    80)
            ->addInlineParser(new InlineParser\EntityParser(),       70)
            ->addInlineParser(new InlineParser\AutolinkParser(),     50)
            ->addInlineParser(new InlineParser\HtmlInlineParser(),   40)
            ->addInlineParser(new InlineParser\CloseBracketParser(), 30)
            ->addInlineParser(new InlineParser\OpenBracketParser(),  20)
            ->addInlineParser(new InlineParser\BangParser(),         10)

            ->addBlockRenderer(BlockElement\BlockQuote::class,    new BlockRenderer\BlockQuoteRenderer(),    0)
            ->addBlockRenderer(BlockElement\Document::class,      new BlockRenderer\DocumentRenderer(),      0)
            ->addBlockRenderer(BlockElement\FencedCode::class,    new BlockRenderer\FencedCodeRenderer(),    0)
            ->addBlockRenderer(BlockElement\Heading::class,       new BlockRenderer\HeadingRenderer(),       0)
            ->addBlockRenderer(BlockElement\HtmlBlock::class,     new BlockRenderer\HtmlBlockRenderer(),     0)
            ->addBlockRenderer(BlockElement\IndentedCode::class,  new BlockRenderer\IndentedCodeRenderer(),  0)
            ->addBlockRenderer(BlockElement\ListBlock::class,     new BlockRenderer\ListBlockRenderer(),     0)
            ->addBlockRenderer(BlockElement\ListItem::class,      new BlockRenderer\ListItemRenderer(),      0)
            ->addBlockRenderer(BlockElement\Paragraph::class,     new BlockRenderer\ParagraphRenderer(),     0)
            ->addBlockRenderer(BlockElement\ThematicBreak::class, new BlockRenderer\ThematicBreakRenderer(), 0)

            ->addInlineRenderer(InlineElement\Code::class,       new InlineRenderer\CodeRenderer(),       0)
            ->addInlineRenderer(InlineElement\Emphasis::class,   new InlineRenderer\EmphasisRenderer(),   0)
            ->addInlineRenderer(InlineElement\HtmlInline::class, new InlineRenderer\HtmlInlineRenderer(), 0)
            ->addInlineRenderer(InlineElement\Image::class,      new InlineRenderer\ImageRenderer(),      0)
            ->addInlineRenderer(InlineElement\Link::class,       new InlineRenderer\LinkRenderer(),       0)
            ->addInlineRenderer(InlineElement\Newline::class,    new InlineRenderer\NewlineRenderer(),    0)
            ->addInlineRenderer(InlineElement\Strong::class,     new InlineRenderer\StrongRenderer(),     0)
            ->addInlineRenderer(InlineElement\Text::class,       new InlineRenderer\TextRenderer(),       0)
        ;

        if ($environment->getConfig('use_asterisk', true)) {
            $environment->addDelimiterProcessor(new EmphasisDelimiterProcessor('*'));
        }

        if ($environment->getConfig('use_underscore', true)) {
            $environment->addDelimiterProcessor(new EmphasisDelimiterProcessor('_'));
        }
    }
}

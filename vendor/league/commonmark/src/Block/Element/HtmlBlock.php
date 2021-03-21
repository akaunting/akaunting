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
use League\CommonMark\Util\RegexHelper;

class HtmlBlock extends AbstractStringContainerBlock
{
    // Any changes to these constants should be reflected in .phpstorm.meta.php
    const TYPE_1_CODE_CONTAINER = 1;
    const TYPE_2_COMMENT = 2;
    const TYPE_3 = 3;
    const TYPE_4 = 4;
    const TYPE_5_CDATA = 5;
    const TYPE_6_BLOCK_ELEMENT = 6;
    const TYPE_7_MISC_ELEMENT = 7;

    /**
     * @var int
     */
    protected $type;

    /**
     * @param int $type
     */
    public function __construct(int $type)
    {
        parent::__construct();

        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return void
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }

    public function canContain(AbstractBlock $block): bool
    {
        return false;
    }

    public function isCode(): bool
    {
        return true;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        if ($cursor->isBlank() && ($this->type === self::TYPE_6_BLOCK_ELEMENT || $this->type === self::TYPE_7_MISC_ELEMENT)) {
            return false;
        }

        return true;
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        $this->finalStringContents = \implode("\n", $this->strings->toArray());
    }

    public function handleRemainingContents(ContextInterface $context, Cursor $cursor)
    {
        /** @var self $tip */
        $tip = $context->getTip();
        $tip->addLine($cursor->getRemainder());

        // Check for end condition
        if ($this->type >= self::TYPE_1_CODE_CONTAINER && $this->type <= self::TYPE_5_CDATA) {
            if ($cursor->match(RegexHelper::getHtmlBlockCloseRegex($this->type)) !== null) {
                $this->finalize($context, $context->getLineNumber());
            }
        }
    }
}

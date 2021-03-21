<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 * (c) 2015 Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace League\CommonMark\Extension\Attributes\Node;

use League\CommonMark\Inline\Element\AbstractInline;

final class AttributesInline extends AbstractInline
{
    /** @var array<string, mixed> */
    public $attributes;

    /** @var bool */
    public $block;

    /**
     * @param array<string, mixed> $attributes
     * @param bool                 $block
     */
    public function __construct(array $attributes, bool $block)
    {
        $this->attributes = $attributes;
        $this->block = $block;
        $this->data = ['delim' => true]; // TODO: Re-implement as a delimiter?
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function isBlock(): bool
    {
        return $this->block;
    }
}

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

namespace League\CommonMark\Reference;

use League\CommonMark\Normalizer\TextNormalizer;

final class Reference implements ReferenceInterface
{
    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @var string
     */
    protected $title;

    public function __construct(string $label, string $destination, string $title)
    {
        $this->label = $label;
        $this->destination = $destination;
        $this->title = $title;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Normalize reference label
     *
     * This enables case-insensitive label matching
     *
     * @param string $string
     *
     * @return string
     *
     * @deprecated Use TextNormalizer::normalize() instead
     * @group legacy
     */
    public static function normalizeReference(string $string): string
    {
        @trigger_error(sprintf('%s::normlizeReference() is deprecated; use %s::normalize() instead', self::class, TextNormalizer::class), E_USER_DEPRECATED);

        return (new TextNormalizer())->normalize($string);
    }
}

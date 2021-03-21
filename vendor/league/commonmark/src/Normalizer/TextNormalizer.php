<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace League\CommonMark\Normalizer;

/***
 * Normalize text input using the steps given by the CommonMark spec to normalize labels
 *
 * @see https://spec.commonmark.org/0.29/#matches
 */
final class TextNormalizer implements TextNormalizerInterface
{
    /**
     * @var array<int, array<int, string>>
     *
     * Source: https://github.com/symfony/polyfill-mbstring/blob/master/Mbstring.php
     */
    private const CASE_FOLD = [
        ['µ', 'ſ', "\xCD\x85", 'ς', "\xCF\x90", "\xCF\x91", "\xCF\x95", "\xCF\x96", "\xCF\xB0", "\xCF\xB1", "\xCF\xB5", "\xE1\xBA\x9B", "\xE1\xBE\xBE", "\xC3\x9F", "\xE1\xBA\x9E"],
        ['μ', 's', 'ι',        'σ', 'β',        'θ',        'φ',        'π',        'κ',        'ρ',        'ε',        "\xE1\xB9\xA1", 'ι',            'ss',       'ss'],
    ];

    /**
     * {@inheritdoc}
     */
    public function normalize(string $text, $context = null): string
    {
        // Collapse internal whitespace to single space and remove
        // leading/trailing whitespace
        $text = \preg_replace('/\s+/', ' ', \trim($text));

        if (!\defined('MB_CASE_FOLD')) {
            // We're not on a version of PHP (7.3+) which has this feature
            $text = \str_replace(self::CASE_FOLD[0], self::CASE_FOLD[1], $text);

            return \mb_strtolower($text, 'UTF-8');
        }

        return \mb_convert_case($text, \MB_CASE_FOLD, 'UTF-8');
    }
}

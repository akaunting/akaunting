<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Markdown.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Markdown\View\Directive;

use League\CommonMark\ConverterInterface;

/**
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class CommonMarkDirective implements DirectiveInterface
{
    private ConverterInterface $converter;

    /**
     * @param \League\CommonMark\ConverterInterface $converter
     *
     * @return void
     */
    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Normalize and render the markdown.
     *
     * @param string $markdown
     *
     * @return string
     */
    public function render(string $markdown): string
    {
        return $this->converter->convert(self::adjust($markdown))->getContent();
    }

    /**
     * Adjust for indentation.
     *
     * @param string $markdown
     *
     * @return string
     */
    private static function adjust(string $markdown): string
    {
        $lines = preg_split("/(\r\n|\n|\r)/", $markdown);

        if (!$lines) {
            return $markdown;
        }

        $last = array_values(array_slice($lines, -1))[0];
        if ($indent = trim($last) === '' ? $last : '') {
            $len = strlen($indent);
            foreach ($lines as $key => $value) {
                if (substr($value, 0, $len) === $indent) {
                    $lines[$key] = substr($value, $len);
                } elseif (trim($value) !== '') {
                    return $markdown; // bail out
                }
            }
        }

        return implode("\n", $lines);
    }
}

<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Formatter;

use Psy\Exception\RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * A pretty-printer for code.
 */
class CodeFormatter implements ReflectorFormatter
{
    const LINE_MARKER = '  <urgent>></urgent> ';
    const NO_LINE_MARKER = '    ';

    const HIGHLIGHT_DEFAULT = 'default';
    const HIGHLIGHT_KEYWORD = 'keyword';

    const HIGHLIGHT_PUBLIC = 'public';
    const HIGHLIGHT_PROTECTED = 'protected';
    const HIGHLIGHT_PRIVATE = 'private';

    const HIGHLIGHT_CONST = 'const';
    const HIGHLIGHT_NUMBER = 'number';
    const HIGHLIGHT_STRING = 'string';
    const HIGHLIGHT_COMMENT = 'code_comment';
    const HIGHLIGHT_INLINE_HTML = 'inline_html';

    private static $tokenMap = [
        // Not highlighted
        \T_OPEN_TAG           => self::HIGHLIGHT_DEFAULT,
        \T_OPEN_TAG_WITH_ECHO => self::HIGHLIGHT_DEFAULT,
        \T_CLOSE_TAG          => self::HIGHLIGHT_DEFAULT,
        \T_STRING             => self::HIGHLIGHT_DEFAULT,
        \T_VARIABLE           => self::HIGHLIGHT_DEFAULT,
        \T_NS_SEPARATOR       => self::HIGHLIGHT_DEFAULT,

        // Visibility
        \T_PUBLIC    => self::HIGHLIGHT_PUBLIC,
        \T_PROTECTED => self::HIGHLIGHT_PROTECTED,
        \T_PRIVATE   => self::HIGHLIGHT_PRIVATE,

        // Constants
        \T_DIR      => self::HIGHLIGHT_CONST,
        \T_FILE     => self::HIGHLIGHT_CONST,
        \T_METHOD_C => self::HIGHLIGHT_CONST,
        \T_NS_C     => self::HIGHLIGHT_CONST,
        \T_LINE     => self::HIGHLIGHT_CONST,
        \T_CLASS_C  => self::HIGHLIGHT_CONST,
        \T_FUNC_C   => self::HIGHLIGHT_CONST,
        \T_TRAIT_C  => self::HIGHLIGHT_CONST,

        // Types
        \T_DNUMBER                  => self::HIGHLIGHT_NUMBER,
        \T_LNUMBER                  => self::HIGHLIGHT_NUMBER,
        \T_ENCAPSED_AND_WHITESPACE  => self::HIGHLIGHT_STRING,
        \T_CONSTANT_ENCAPSED_STRING => self::HIGHLIGHT_STRING,

        // Comments
        \T_COMMENT     => self::HIGHLIGHT_COMMENT,
        \T_DOC_COMMENT => self::HIGHLIGHT_COMMENT,

        // @todo something better here?
        \T_INLINE_HTML => self::HIGHLIGHT_INLINE_HTML,
    ];

    /**
     * Format the code represented by $reflector for shell output.
     *
     * @param \Reflector  $reflector
     * @param string|null $colorMode (deprecated and ignored)
     *
     * @return string formatted code
     */
    public static function format(\Reflector $reflector, string $colorMode = null): string
    {
        if (self::isReflectable($reflector)) {
            if ($code = @\file_get_contents($reflector->getFileName())) {
                return self::formatCode($code, self::getStartLine($reflector), $reflector->getEndLine());
            }
        }

        throw new RuntimeException('Source code unavailable');
    }

    /**
     * Format code for shell output.
     *
     * Optionally, restrict by $startLine and $endLine line numbers, or pass $markLine to add a line marker.
     *
     * @param string   $code
     * @param int      $startLine
     * @param int|null $endLine
     * @param int|null $markLine
     *
     * @return string formatted code
     */
    public static function formatCode(string $code, int $startLine = 1, int $endLine = null, int $markLine = null): string
    {
        $spans = self::tokenizeSpans($code);
        $lines = self::splitLines($spans, $startLine, $endLine);
        $lines = self::formatLines($lines);
        $lines = self::numberLines($lines, $markLine);

        return \implode('', \iterator_to_array($lines));
    }

    /**
     * Get the start line for a given Reflector.
     *
     * Tries to incorporate doc comments if possible.
     *
     * This is typehinted as \Reflector but we've narrowed the input via self::isReflectable already.
     *
     * @param \ReflectionClass|\ReflectionFunctionAbstract $reflector
     */
    private static function getStartLine(\Reflector $reflector): int
    {
        $startLine = $reflector->getStartLine();

        if ($docComment = $reflector->getDocComment()) {
            $startLine -= \preg_match_all('/(\r\n?|\n)/', $docComment) + 1;
        }

        return \max($startLine, 1);
    }

    /**
     * Split code into highlight spans.
     *
     * Tokenize via \token_get_all, then map these tokens to internal highlight types, combining
     * adjacent spans of the same highlight type.
     *
     * @todo consider switching \token_get_all() out for PHP-Parser-based formatting at some point.
     *
     * @param string $code
     *
     * @return \Generator [$spanType, $spanText] highlight spans
     */
    private static function tokenizeSpans(string $code): \Generator
    {
        $spanType = null;
        $buffer = '';

        foreach (\token_get_all($code) as $token) {
            $nextType = self::nextHighlightType($token, $spanType);
            $spanType = $spanType ?: $nextType;

            if ($spanType !== $nextType) {
                yield [$spanType, $buffer];
                $spanType = $nextType;
                $buffer = '';
            }

            $buffer .= \is_array($token) ? $token[1] : $token;
        }

        if ($spanType !== null && $buffer !== '') {
            yield [$spanType, $buffer];
        }
    }

    /**
     * Given a token and the current highlight span type, compute the next type.
     *
     * @param array|string $token       \token_get_all token
     * @param string|null  $currentType
     *
     * @return string|null
     */
    private static function nextHighlightType($token, $currentType)
    {
        if ($token === '"') {
            return self::HIGHLIGHT_STRING;
        }

        if (\is_array($token)) {
            if ($token[0] === \T_WHITESPACE) {
                return $currentType;
            }

            if (\array_key_exists($token[0], self::$tokenMap)) {
                return self::$tokenMap[$token[0]];
            }
        }

        return self::HIGHLIGHT_KEYWORD;
    }

    /**
     * Group highlight spans into an array of lines.
     *
     * Optionally, restrict by start and end line numbers.
     *
     * @param \Generator $spans     as [$spanType, $spanText] pairs
     * @param int        $startLine
     * @param int|null   $endLine
     *
     * @return \Generator lines, each an array of [$spanType, $spanText] pairs
     */
    private static function splitLines(\Generator $spans, int $startLine = 1, int $endLine = null): \Generator
    {
        $lineNum = 1;
        $buffer = [];

        foreach ($spans as list($spanType, $spanText)) {
            foreach (\preg_split('/(\r\n?|\n)/', $spanText) as $index => $spanLine) {
                if ($index > 0) {
                    if ($lineNum >= $startLine) {
                        yield $lineNum => $buffer;
                    }

                    $lineNum++;
                    $buffer = [];

                    if ($endLine !== null && $lineNum > $endLine) {
                        return;
                    }
                }

                if ($spanLine !== '') {
                    $buffer[] = [$spanType, $spanLine];
                }
            }
        }

        if (!empty($buffer)) {
            yield $lineNum => $buffer;
        }
    }

    /**
     * Format lines of highlight spans for shell output.
     *
     * @param \Generator $spanLines lines, each an array of [$spanType, $spanText] pairs
     *
     * @return \Generator Formatted lines
     */
    private static function formatLines(\Generator $spanLines): \Generator
    {
        foreach ($spanLines as $lineNum => $spanLine) {
            $line = '';

            foreach ($spanLine as list($spanType, $spanText)) {
                if ($spanType === self::HIGHLIGHT_DEFAULT) {
                    $line .= OutputFormatter::escape($spanText);
                } else {
                    $line .= \sprintf('<%s>%s</%s>', $spanType, OutputFormatter::escape($spanText), $spanType);
                }
            }

            yield $lineNum => $line.\PHP_EOL;
        }
    }

    /**
     * Prepend line numbers to formatted lines.
     *
     * Lines must be in an associative array with the correct keys in order to be numbered properly.
     *
     * Optionally, pass $markLine to add a line marker.
     *
     * @param \Generator $lines    Formatted lines
     * @param int|null   $markLine
     *
     * @return \Generator Numbered, formatted lines
     */
    private static function numberLines(\Generator $lines, int $markLine = null): \Generator
    {
        $lines = \iterator_to_array($lines);

        // Figure out how much space to reserve for line numbers.
        \end($lines);
        $pad = \strlen(\key($lines));

        // If $markLine is before or after our line range, don't bother reserving space for the marker.
        if ($markLine !== null) {
            if ($markLine > \key($lines)) {
                $markLine = null;
            }

            \reset($lines);
            if ($markLine < \key($lines)) {
                $markLine = null;
            }
        }

        foreach ($lines as $lineNum => $line) {
            $mark = '';
            if ($markLine !== null) {
                $mark = ($markLine === $lineNum) ? self::LINE_MARKER : self::NO_LINE_MARKER;
            }

            yield \sprintf("%s<aside>%{$pad}s</aside>: %s", $mark, $lineNum, $line);
        }
    }

    /**
     * Check whether a Reflector instance is reflectable by this formatter.
     *
     * @phpstan-assert-if-true \ReflectionClass|\ReflectionFunctionAbstract $reflector
     *
     * @param \Reflector $reflector
     */
    private static function isReflectable(\Reflector $reflector): bool
    {
        return ($reflector instanceof \ReflectionClass || $reflector instanceof \ReflectionFunctionAbstract) && \is_file($reflector->getFileName());
    }
}

<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PrintCssTest extends TestCase
{
    public function test_directional_alignment_rules_are_compatible_with_pdf_renderers(): void
    {
        $css = file_get_contents(dirname(__DIR__, 2) . '/public/css/print.css');

        self::assertIsString($css);

        foreach ([
            ['[dir="ltr"] [class~="ltr:float-left"]', 'float: left !important;'],
            ['[dir="ltr"] [class~="ltr:float-right"]', 'float: right !important;'],
            ['[dir="rtl"] [class~="rtl:float-left"]', 'float: left !important;'],
            ['[dir="rtl"] [class~="rtl:float-right"]', 'float: right !important;'],
            ['[dir="ltr"] [class~="ltr:text-left"]', 'text-align: left;'],
            ['[dir="ltr"] [class~="ltr:text-right"]', 'text-align: right;'],
            ['[dir="rtl"] [class~="rtl:text-left"]', 'text-align: left;'],
            ['[dir="rtl"] [class~="rtl:text-right"]', 'text-align: right;'],
        ] as [$selector, $declaration]) {
            self::assertMatchesRegularExpression(
                '/^[ \t]*' . preg_quote($selector, '/') . '\s*\{\s*' . preg_quote($declaration, '/') . '\s*\}/m',
                $css,
                sprintf('%s must have the expected renderer-compatible declaration.', $selector),
            );
        }

        self::assertDoesNotMatchRegularExpression(
            '/html\[dir="rtl"\]\s+\[class~="ltr:float-(left|right)"\]/',
            $css,
            'LTR float fallbacks must not apply to RTL documents.',
        );

        self::assertDoesNotMatchRegularExpression(
            '/html\[dir="(ltr|rtl)"\]\s+\[class~="(ltr|rtl):(float|text)-(left|right)"\]/',
            $css,
            'Directional class-token fallbacks must use the renderer-compatible direction selector.',
        );
    }
}

<?php

namespace Lorisleiva\LaravelSearchString\Tests;

class LexerTest extends TestCase
{
    public function success()
    {
        return [
            // Strings.
            ['"Hello world"', 'T_DOUBLE_LQUOTE T_STRING T_DOUBLE_RQUOTE'],
            ["'Hello world'", 'T_SINGLE_LQUOTE T_STRING T_SINGLE_RQUOTE'],

            // Assignments.
            ['foo:bar', 'T_TERM T_ASSIGNMENT T_TERM'],
            ['foo=bar', 'T_TERM T_ASSIGNMENT T_TERM'],
            ["foo:'bar'", 'T_TERM T_ASSIGNMENT T_SINGLE_LQUOTE T_STRING T_SINGLE_RQUOTE'],

            // Ignores spaces.
            [' foo = bar ', 'T_TERM T_ASSIGNMENT T_TERM'],

            // Parenthesis.
            ['(foo:bar)', 'T_LPARENTHESIS T_TERM T_ASSIGNMENT T_TERM T_RPARENTHESIS'],

            // Comparisons.
            ['foo<bar', 'T_TERM T_COMPARATOR T_TERM'],
            ['foo<=bar', 'T_TERM T_COMPARATOR T_TERM'],
            ['foo>bar', 'T_TERM T_COMPARATOR T_TERM'],
            ['foo>=bar', 'T_TERM T_COMPARATOR T_TERM'],
            ['foo<"bar"', 'T_TERM T_COMPARATOR T_DOUBLE_LQUOTE T_STRING T_DOUBLE_RQUOTE'],

            // Boolean operators.
            ['foo and bar', 'T_TERM T_AND T_TERM'],
            ['foo or bar', 'T_TERM T_OR T_TERM'],
            ['foo and not bar', 'T_TERM T_AND T_NOT T_TERM'],

            // Lists.
            ['foo in (a,b,c)', 'T_TERM T_IN T_LPARENTHESIS T_TERM T_COMMA T_TERM T_COMMA T_TERM T_RPARENTHESIS'],

            // Complex examples.
            [
                'foo12bar.x.y?z and (foo:1 or bar> 3.5)',
                'T_TERM T_DOT T_TERM T_DOT T_TERM T_AND T_LPARENTHESIS T_TERM T_ASSIGNMENT T_INTEGER T_OR T_TERM T_COMPARATOR T_DECIMAL T_RPARENTHESIS'
            ],

            // Greedy on terms.
            ['and', 'T_AND'],
            ['andora', 'T_TERM'],
            ['or', 'T_OR'],
            ['oracle', 'T_TERM'],
            ['not', 'T_NOT'],
            ['notice', 'T_TERM'],

            // Terminating keywords.
            ['and', 'T_AND'],
            ['or', 'T_OR'],
            ['not', 'T_NOT'],
            ['in', 'T_IN'],
            ['and)', 'T_AND T_RPARENTHESIS'],
            ['or)', 'T_OR T_RPARENTHESIS'],
            ['not)', 'T_NOT T_RPARENTHESIS'],
            ['in)', 'T_IN T_RPARENTHESIS'],
        ];
    }

    /**
     * @test
     * @dataProvider success
     * @param $input
     * @param $expectedTokens
     */
    public function lexer_success($input, $expectedTokens)
    {
        $tokens = $this->lex($input)->map->token->all();
        array_pop($tokens); // Ignore EOF token.
        $this->assertEquals($expectedTokens, implode(' ', $tokens));
    }
}

<?php

namespace Lorisleiva\LaravelSearchString\Tests;

use Lorisleiva\LaravelSearchString\Visitors\InlineDumpVisitor;
use Lorisleiva\LaravelSearchString\Visitors\RemoveNotSymbolVisitor;

/**
 * @see RemoveNotSymbolVisitor
 */
class VisitorRemoveNotSymbolTest extends VisitorTest
{
    public function visitors($manager, $builder, $model)
    {
        return [
            new RemoveNotSymbolVisitor(),
            new InlineDumpVisitor(),
        ];
    }

    public function success()
    {
        return [
            // Negate query symbols.
            ['not foo:bar', 'QUERY(foo != bar)'],
            ['not foo:-1,2,3', 'LIST(foo not in [-1, 2, 3])'],
            ['not foo="bar"', 'QUERY(foo != bar)'],
            ['not foo<1', 'QUERY(foo >= 1)'],
            ['not foo>1', 'QUERY(foo <= 1)'],
            ['not foo<=1', 'QUERY(foo > 1)'],
            ['not foo>=1', 'QUERY(foo < 1)'],
            ['not foo in(1, 2, 3)', 'LIST(foo not in [1, 2, 3])'],

            // Negate solo symbols.
            ['foobar', 'SOLO(foobar)'],
            ['not foobar', 'SOLO_NOT(foobar)'],
            ['"John Doe"', 'SOLO(John Doe)'],
            ['not "John Doe"', 'SOLO_NOT(John Doe)'],

            // Negate and/or symbols.
            ['not (A and B)', 'OR(SOLO_NOT(A), SOLO_NOT(B))'],
            ['not (A and not B)', 'OR(SOLO_NOT(A), SOLO(B))'],
            ['not (A or B)', 'AND(SOLO_NOT(A), SOLO_NOT(B))'],
            ['not (A or not B)', 'AND(SOLO_NOT(A), SOLO(B))'],
            ['not (A or (B and C))', 'AND(SOLO_NOT(A), OR(SOLO_NOT(B), SOLO_NOT(C)))'],
            ['not (A and (B or C))', 'OR(SOLO_NOT(A), AND(SOLO_NOT(B), SOLO_NOT(C)))'],
            ['not (A and not B and not C and D)', 'OR(SOLO_NOT(A), SOLO(B), SOLO(C), SOLO_NOT(D))'],
            ['not (A or not B or not C or D)', 'AND(SOLO_NOT(A), SOLO(B), SOLO(C), SOLO_NOT(D))'],
            ['not ((A or not B) and not (not C and D))', 'OR(AND(SOLO_NOT(A), SOLO(B)), AND(SOLO_NOT(C), SOLO(D)))'],

            // Cancel the negation of another not.
            ['not not foo:bar', 'QUERY(foo = bar)'],
            ['not not foo:-1,2,3', 'LIST(foo in [-1, 2, 3])'],
            ['not not foo="bar"', 'QUERY(foo = bar)'],
            ['not not foo<1', 'QUERY(foo < 1)'],
            ['not not foo>1', 'QUERY(foo > 1)'],
            ['not not foo<=1', 'QUERY(foo <= 1)'],
            ['not not foo>=1', 'QUERY(foo >= 1)'],
            ['not not foo in(1, 2, 3)', 'LIST(foo in [1, 2, 3])'],
            ['not not (A and B)', 'AND(SOLO(A), SOLO(B))'],
            ['not not (A or B)', 'OR(SOLO(A), SOLO(B))'],
            ['not not foobar', 'SOLO(foobar)'],
            ['not not "John Doe"', 'SOLO(John Doe)'],
            ['not not not "John Doe"', 'SOLO_NOT(John Doe)'],
            ['not not not not "John Doe"', 'SOLO(John Doe)'],

            // Relationships.
            ['not comments.author', 'NOT_EXISTS(comments, SOLO(author))'],
            ['not comments.author = "John Doe"', 'NOT_EXISTS(comments, QUERY(author = John Doe))'],
            ['not comments.author.tags', 'NOT_EXISTS(comments, EXISTS(author, SOLO(tags)))'],
            ['comments: (not achievements: (Laravel))', 'EXISTS(comments, NOT_EXISTS(achievements, SOLO(Laravel)))'],
            ['not comments: (achievements: (Laravel))', 'NOT_EXISTS(comments, EXISTS(achievements, SOLO(Laravel)))'],
            ['not comments: (not (A or not B) or not D)', 'NOT_EXISTS(comments, OR(AND(SOLO_NOT(A), SOLO(B)), SOLO_NOT(D)))'],
        ];
    }

    /**
     * @test
     * @dataProvider success
     * @param $input
     * @param $expected
     */
    public function visitor_remove_not_symbol_success($input, $expected)
    {
        $this->assertAstEquals($input, $expected);
    }
}

<?php

namespace Lorisleiva\LaravelSearchString\Tests;

use Lorisleiva\LaravelSearchString\AST\AndSymbol;
use Lorisleiva\LaravelSearchString\AST\EmptySymbol;
use Lorisleiva\LaravelSearchString\AST\NotSymbol;
use Lorisleiva\LaravelSearchString\AST\OrSymbol;
use Lorisleiva\LaravelSearchString\AST\QuerySymbol;
use Lorisleiva\LaravelSearchString\Visitors\AttachRulesVisitor;
use Lorisleiva\LaravelSearchString\Visitors\IdentifyRelationshipsFromRulesVisitor;
use Lorisleiva\LaravelSearchString\Visitors\InlineDumpVisitor;
use Lorisleiva\LaravelSearchString\Visitors\OptimizeAstVisitor;
use Lorisleiva\LaravelSearchString\Visitors\RemoveNotSymbolVisitor;

/**
 * @see OptimizeAstVisitor
 */
class VisitorOptimizeAstTest extends VisitorTest
{
    public function visitors($manager, $builder, $model)
    {
        return [
            new AttachRulesVisitor($manager),
            new IdentifyRelationshipsFromRulesVisitor(),
            new RemoveNotSymbolVisitor(),
            new OptimizeAstVisitor(),
            new InlineDumpVisitor(true),
        ];
    }

    public function success()
    {
        return [
            // Flatten And/Or.
            ['A and (B and (C and D))', 'AND(A, B, C, D)'],
            ['A or (B or (C or D))', 'OR(A, B, C, D)'],
            ['A and (B and C) or D', 'OR(AND(A, B, C), D)'],
            ['(A or (B or C)) and D', 'AND(OR(A, B, C), D)'],
            ['A or (B or C) and D or E', 'OR(A, AND(OR(B, C), D), E)'],
            ['(A or B) or (C and D or E)', 'OR(A, B, AND(C, D), E)'],
            ['(A and B) and C and (D or E)', 'AND(A, B, C, OR(D, E))'],

            // Inline And/Or with only one element.
            [new AndSymbol([new QuerySymbol('foo', '=', 'bar')]), 'foo = bar'],
            [new OrSymbol([new QuerySymbol('foo', '=', 'bar')]), 'foo = bar'],

            // Remove Or with no children.
            [new OrSymbol, 'EMPTY'],
            [new OrSymbol([new AndSymbol, new AndSymbol]), 'EMPTY'],
            [new OrSymbol([new EmptySymbol]), 'EMPTY'],

            // Remove And with no children.
            [new AndSymbol, 'EMPTY'],
            [new AndSymbol([new OrSymbol, new OrSymbol]), 'EMPTY'],
            [new AndSymbol([new EmptySymbol]), 'EMPTY'],

            // Remove Not with no children.
            [new NotSymbol(new OrSymbol), 'EMPTY'],
            [new NotSymbol(new NotSymbol(new AndSymbol)), 'EMPTY'],
            [new NotSymbol(new EmptySymbol), 'EMPTY'],

            // Flatten relationship existance.
            ['comments and comments', 'EXISTS(comments, EMPTY)'],
            ['comments and comments or comments', 'EXISTS(comments, EMPTY)'],
            ['(comments or comments) or comments or (comments and comments or comments)', 'EXISTS(comments, EMPTY)'],
            ['comments > 0 and comments >= 1 and comments and not comments = 0', 'EXISTS(comments, EMPTY)'],
            ['comments and not (comments = 0 or not comments)', 'EXISTS(comments, EMPTY)'],

            // Flatten relationship inexistance.
            ['not comments and not comments', 'NOT_EXISTS(comments, EMPTY)'],
            ['not comments and not (comments or comments)', 'NOT_EXISTS(comments, EMPTY)'],
            ['comments = 0 and comments < 1 and comments <= 0 and not comments and not comments > 0', 'NOT_EXISTS(comments, EMPTY)'],

            // Flatten relationship count.
            ['comments > 1 and comments > 1', 'EXISTS(comments, EMPTY) > 1'],
            ['comments > 1 and comments >= 2', 'EXISTS(comments, EMPTY) > 1'],
            ['comments >= 2 and comments > 1', 'EXISTS(comments, EMPTY) >= 2'],
            ['comments = 2 and comments = 2', 'EXISTS(comments, EMPTY) = 2'],

            // Flatten relationships complex examples.
            ['comments.title = A comments.title = B', 'EXISTS(comments, AND(title = A, title = B))'],
            ['comments.title = A or comments.title = B', 'EXISTS(comments, OR(title = A, title = B))'],
            ['comments.title = A comments.title = B and foobar', 'AND(EXISTS(comments, AND(title = A, title = B)), foobar)'],
            ['comments.author.name = John and comments.title = "My Comment"', 'EXISTS(comments, AND(EXISTS(author, name = John), title = My Comment))'],
            ['comments.author.name = John and comments.author.name = Jane', 'EXISTS(comments, EXISTS(author, AND(name = John, name = Jane)))'],
            ['comments.author.name = John or comments.author.name = Jane', 'EXISTS(comments, EXISTS(author, OR(name = John, name = Jane)))'],
            ['(comments.title = A and comments.title = B) and (comments.title = C and comments.title = D)', 'EXISTS(comments, AND(title = A, title = B, title = C, title = D))'],
            ['(comments.title = A or comments.title = B) or (comments.title = C or comments.title = D)', 'EXISTS(comments, OR(title = A, title = B, title = C, title = D))'],

            // Keep relationships separate if merging them changes the behavious of the query.
            ['comments and not comments', 'AND(EXISTS(comments, EMPTY), NOT_EXISTS(comments, EMPTY))'],
            ['comments and comments > 10', 'AND(EXISTS(comments, EMPTY), EXISTS(comments, EMPTY) > 10)'],
            ['comments = 3 or not comments = 3', 'OR(EXISTS(comments, EMPTY) = 3, EXISTS(comments, EMPTY) != 3)'],
            ['comments.title = A or comments > 10', 'OR(EXISTS(comments, title = A), EXISTS(comments, EMPTY) > 10)'],
            ['comments.title = A foobar comments > 10', 'AND(EXISTS(comments, title = A), foobar, EXISTS(comments, EMPTY) > 10)'],
            ['comments.title = A or comments.title = B price > 10', 'OR(EXISTS(comments, title = A), AND(EXISTS(comments, title = B), price > 10))'],
        ];
    }

    /**
     * @test
     * @dataProvider success
     * @param $input
     * @param $expected
     */
    public function visitor_optimize_ast_success($input, $expected)
    {
        $this->assertAstEquals($input, $expected);
    }
}

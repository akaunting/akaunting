<?php

namespace Lorisleiva\LaravelSearchString\Tests;

use Lorisleiva\LaravelSearchString\Exceptions\InvalidSearchStringException;
use Lorisleiva\LaravelSearchString\Visitors\AttachRulesVisitor;
use Lorisleiva\LaravelSearchString\Visitors\IdentifyRelationshipsFromRulesVisitor;
use Lorisleiva\LaravelSearchString\Visitors\ValidateRulesVisitor;

class VisitorValidateRulesTest extends VisitorTest
{
    public function visitors($manager, $builder, $model)
    {
        return [
            new AttachRulesVisitor($manager),
            new IdentifyRelationshipsFromRulesVisitor(),
            new ValidateRulesVisitor(),
        ];
    }

    public function failure()
    {
        return [
            // QuerySymbol.
            ['unknown_column = 0'],
            ['unknown_column > 0'],
            ['not unknown_column = 3'],
            ['unknown_column = Something or name = Hi'],

            // ListSymbol.
            ['unknown_column: 1,2,3'],
            ['unknown_column in (a, b, c)'],
            ['not unknown_column = foo, "bar"'],
            ['not unknown_column in (1, "two", three)'],

            // RelationshipSymbol.
            ['unknown_column.title = 3'],
            ['comments.unknown_column = 3'],
            ['comments: (unknown_column = foo)'],
            ['unknown_column: (title = foo)'],
            ['not unknown_column: (author.name = John)'],
        ];
    }

    /**
     * @test
     * @dataProvider failure
     * @param $input
     */
    public function visitor_validate_rules_failure($input)
    {
        $this->expectException(InvalidSearchStringException::class);
        $this->expectExceptionMessage('Unrecognized key pattern [unknown_column]');
        $this->visit($input);
    }
}

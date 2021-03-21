<?php

namespace Lorisleiva\LaravelSearchString\Visitors;

use Lorisleiva\LaravelSearchString\AST\ListSymbol;
use Lorisleiva\LaravelSearchString\AST\RelationshipSymbol;
use Lorisleiva\LaravelSearchString\Exceptions\InvalidSearchStringException;
use Lorisleiva\LaravelSearchString\AST\QuerySymbol;

class ValidateRulesVisitor extends Visitor
{
    public function visitRelationship(RelationshipSymbol $relationship)
    {
        if (! $relationship->rule) {
            throw InvalidSearchStringException::fromVisitor(sprintf('Unrecognized key pattern [%s]', $relationship->key));
        }

        $relationship->expression->accept($this);

        return $relationship;
    }

    public function visitQuery(QuerySymbol $query)
    {
        if (! $query->rule) {
            throw InvalidSearchStringException::fromVisitor(sprintf('Unrecognized key pattern [%s]', $query->key));
        }

        return $query;
    }

    public function visitList(ListSymbol $list)
    {
        if (! $list->rule) {
            throw InvalidSearchStringException::fromVisitor(sprintf('Unrecognized key pattern [%s]', $list->key));
        }

        return $list;
    }
}

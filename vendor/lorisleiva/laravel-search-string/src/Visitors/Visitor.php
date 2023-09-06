<?php

namespace Lorisleiva\LaravelSearchString\Visitors;

use Lorisleiva\LaravelSearchString\AST\AndSymbol;
use Lorisleiva\LaravelSearchString\AST\ListSymbol;
use Lorisleiva\LaravelSearchString\AST\NotSymbol;
use Lorisleiva\LaravelSearchString\AST\EmptySymbol;
use Lorisleiva\LaravelSearchString\AST\OrSymbol;
use Lorisleiva\LaravelSearchString\AST\QuerySymbol;
use Lorisleiva\LaravelSearchString\AST\RelationshipSymbol;
use Lorisleiva\LaravelSearchString\AST\SearchSymbol;
use Lorisleiva\LaravelSearchString\AST\SoloSymbol;

abstract class Visitor
{
    public function visitOr(OrSymbol $or)
    {
        return new OrSymbol($or->expressions->map->accept($this));
    }

    public function visitAnd(AndSymbol $and)
    {
        return new AndSymbol($and->expressions->map->accept($this));
    }

    public function visitNot(NotSymbol $not)
    {
        return new NotSymbol($not->expression->accept($this));
    }

    public function visitRelationship(RelationshipSymbol $relationship)
    {
        $relationship->expression = $relationship->expression->accept($this);

        return $relationship;
    }

    public function visitSolo(SoloSymbol $solo)
    {
        return $solo;
    }

    public function visitQuery(QuerySymbol $query)
    {
        return $query;
    }

    public function visitList(ListSymbol $list)
    {
        return $list;
    }

    public function visitEmpty(EmptySymbol $empty)
    {
        return $empty;
    }
}

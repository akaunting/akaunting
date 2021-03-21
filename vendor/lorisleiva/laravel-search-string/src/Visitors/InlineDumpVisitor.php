<?php

namespace Lorisleiva\LaravelSearchString\Visitors;

use Lorisleiva\LaravelSearchString\AST\AndSymbol;
use Lorisleiva\LaravelSearchString\AST\ListSymbol;
use Lorisleiva\LaravelSearchString\AST\NotSymbol;
use Lorisleiva\LaravelSearchString\AST\EmptySymbol;
use Lorisleiva\LaravelSearchString\AST\OrSymbol;
use Lorisleiva\LaravelSearchString\AST\QuerySymbol;
use Lorisleiva\LaravelSearchString\AST\RelationshipSymbol;
use Lorisleiva\LaravelSearchString\AST\SoloSymbol;

class InlineDumpVisitor extends Visitor
{
    protected $shortenQuery;

    public function __construct($shortenQuery = false)
    {
        $this->shortenQuery = $shortenQuery;
    }

    public function visitOr(OrSymbol $or)
    {
        return 'OR(' . collect($or->expressions)->map->accept($this)->implode(', ') . ')';
    }

    public function visitAnd(AndSymbol $and)
    {
        return 'AND(' . collect($and->expressions)->map->accept($this)->implode(', ') . ')';
    }

    public function visitNot(NotSymbol $not)
    {
        return 'NOT(' . $not->expression->accept($this) . ')';
    }

    public function visitRelationship(RelationshipSymbol $relationship)
    {
        $expression = $relationship->expression->accept($this);
        $explicitOperation = ! $relationship->isCheckingExistance() && ! $relationship->isCheckingInexistance();

        return sprintf(
            '%s(%s, %s)%s',
            $relationship->isCheckingInexistance() ? 'NOT_EXISTS' : 'EXISTS',
            $relationship->key,
            $expression,
            $explicitOperation ? (' ' . $relationship->getExpectedOperation()) : '',
        );
    }

    public function visitSolo(SoloSymbol $solo)
    {
        if ($this->shortenQuery) {
            return $solo->content;
        }

        return $solo->negated
            ? "SOLO_NOT($solo->content)"
            : "SOLO($solo->content)";
    }

    public function visitQuery(QuerySymbol $query)
    {
        $value = $query->value;

        if ($this->shortenQuery) {
            $value = is_array($value) ? '[' . implode(', ', $value) . ']' : $value;
            return $query->key . (is_bool($value) ? '' : " $query->operator $value");
        }

        $value = is_bool($value) ? ($value ? 'true' : 'false') : $value;
        $value = is_array($value) ? '[' . implode(', ', $value) . ']' : $value;
        return "QUERY($query->key $query->operator $value)";
    }

    public function visitList(ListSymbol $list)
    {
        $operator = $list->negated ? 'not in' : 'in';
        $dump = sprintf('%s %s [%s]', $list->key, $operator, implode(', ', $list->values));

        return $this->shortenQuery ? $dump : "LIST($dump)";
    }

    public function visitEmpty(EmptySymbol $empty)
    {
        return 'EMPTY';
    }
}

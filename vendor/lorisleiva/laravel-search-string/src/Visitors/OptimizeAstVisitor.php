<?php

namespace Lorisleiva\LaravelSearchString\Visitors;

use Illuminate\Support\Collection;
use Lorisleiva\LaravelSearchString\AST\AndSymbol;
use Lorisleiva\LaravelSearchString\AST\NotSymbol;
use Lorisleiva\LaravelSearchString\AST\EmptySymbol;
use Lorisleiva\LaravelSearchString\AST\OrSymbol;
use Lorisleiva\LaravelSearchString\AST\RelationshipSymbol;
use Lorisleiva\LaravelSearchString\AST\Symbol;

class OptimizeAstVisitor extends Visitor
{
    public function visitOr(OrSymbol $or)
    {
        $leaves = $or->expressions->map->accept($this);
        $leaves = $this->flattenNestedLeaves($leaves, OrSymbol::class);
        $leaves = $this->mergeEquivalentRelationshipSymbols($leaves, OrSymbol::class);

        return $this->getAppropriateSymbolForNestedLeaves($leaves, OrSymbol::class);
    }

    public function visitAnd(AndSymbol $and)
    {
        $leaves = $and->expressions->map->accept($this);
        $leaves = $this->flattenNestedLeaves($leaves, AndSymbol::class);
        $leaves = $this->mergeEquivalentRelationshipSymbols($leaves, AndSymbol::class);

        return $this->getAppropriateSymbolForNestedLeaves($leaves, AndSymbol::class);
    }

    public function visitNot(NotSymbol $not)
    {
        $leaf = $not->expression->accept($this);
        return $leaf instanceof EmptySymbol ? new EmptySymbol : new NotSymbol($leaf);
    }

    public function flattenNestedLeaves(Collection $leaves, string $symbolClass)
    {
        return $leaves
            ->flatMap(function ($leaf) use ($symbolClass) {
                return $leaf instanceof $symbolClass ? $leaf->expressions : [$leaf];
            })
            ->filter(function ($leaf) {
                return ! $leaf instanceof EmptySymbol;
            });
    }

    public function getAppropriateSymbolForNestedLeaves(Collection $leaves, string $symbolClass): Symbol
    {
        $leaves = $leaves->filter(function ($leaf) {
            return ! $leaf instanceof EmptySymbol;
        });

        if ($leaves->isEmpty()) {
            return new EmptySymbol();
        }

        if ($leaves->count() === 1) {
            return $leaves->first();
        }

        return new $symbolClass($leaves);
    }

    public function mergeEquivalentRelationshipSymbols(Collection $leaves, string $symbolClass): Collection
    {
        return $leaves
            ->reduce(function (Collection $acc, Symbol $symbol) {
                if ($group = $this->findRelationshipGroup($acc, $symbol)) {
                    $group->push($symbol);
                } else {
                    $acc->push(collect([$symbol]));
                }

                return $acc;
            }, collect())
            ->map(function (Collection $group) use ($symbolClass) {
                return  $group->count() > 1
                    ? $this->mergeRelationshipGroup($group, $symbolClass)
                    : $group->first();
            });
    }

    public function findRelationshipGroup(Collection $leafGroups, Symbol $symbol): ?Collection
    {
        if (! $symbol instanceof RelationshipSymbol) {
            return null;
        }

        return $leafGroups->first(function (Collection $group) use ($symbol) {
            return $symbol->match($group->first());
        });
    }

    public function mergeRelationshipGroup(Collection $relationshipGroup, string $symbolClass): RelationshipSymbol
    {
        $relationshipSymbol = $relationshipGroup->first();
        $expressions = $relationshipGroup->map->expression;
        $relationshipSymbol->expression = (new $symbolClass($expressions))->accept($this);

        return $relationshipSymbol;
    }
}

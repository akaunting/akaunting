<?php

namespace Lorisleiva\LaravelSearchString\Visitors;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lorisleiva\LaravelSearchString\AST\EmptySymbol;
use Lorisleiva\LaravelSearchString\AST\ListSymbol;
use Lorisleiva\LaravelSearchString\AST\RelationshipSymbol;
use Lorisleiva\LaravelSearchString\Options\ColumnRule;
use Lorisleiva\LaravelSearchString\AST\OrSymbol;
use Lorisleiva\LaravelSearchString\AST\AndSymbol;
use Lorisleiva\LaravelSearchString\AST\SoloSymbol;
use Lorisleiva\LaravelSearchString\AST\QuerySymbol;
use Lorisleiva\LaravelSearchString\SearchStringManager;
use Lorisleiva\LaravelSearchString\Support\DateWithPrecision;

class BuildColumnsVisitor extends Visitor
{
    /** @var SearchStringManager */
    protected $manager;
    protected $builder;
    protected $boolean;

    public function __construct(SearchStringManager $manager, $builder, $boolean = 'and')
    {
        $this->manager = $manager;
        $this->builder = $builder;
        $this->boolean = $boolean;
    }

    public function visitOr(OrSymbol $or)
    {
        $callback = $this->getNestedCallback($or->expressions, 'or');
        $this->builder->where($callback, null, null, $this->boolean);

        return $or;
    }

    public function visitAnd(AndSymbol $and)
    {
        $callback = $this->getNestedCallback($and->expressions, 'and');
        $this->builder->where($callback, null, null, $this->boolean);

        return $and;
    }

    public function visitRelationship(RelationshipSymbol $relationship)
    {
        $this->buildRelationship($relationship);

        return $relationship;
    }

    public function visitSolo(SoloSymbol $solo)
    {
        $this->buildSolo($solo);

        return $solo;
    }

    public function visitQuery(QuerySymbol $query)
    {
        $this->buildQuery($query);

        return $query;
    }

    public function visitList(ListSymbol $list)
    {
        $this->buildList($list);

        return $list;
    }

    protected function getNestedCallback(Collection $expressions, string $newBoolean = 'and', $newManager = null)
    {
        return function ($nestedBuilder) use ($expressions, $newBoolean, $newManager) {

            // Save and update the new builder and boolean.
            $originalBuilder = $this->builder;
            $originalBoolean = $this->boolean;
            $originalManager = $this->manager;
            $this->builder = $nestedBuilder;
            $this->boolean = $newBoolean;
            $this->manager = $newManager ?: $originalManager;

            // Recursively generate the nested builder.
            $expressions->each->accept($this);

            // Restore the original builder and boolean.
            $this->builder = $originalBuilder;
            $this->boolean = $originalBoolean;
            $this->manager = $originalManager;

        };
    }

    protected function buildRelationship(RelationshipSymbol $relationship)
    {
        /** @var ColumnRule $rule */
        if (! $rule = $relationship->rule) {
            return;
        }

        $nestedExpressions = collect([$relationship->expression]);
        $newManager = $rule->relationshipModel->getSearchStringManager();
        $callback = $this->getNestedCallback($nestedExpressions, 'and', $newManager);
        $callback = $relationship->expression instanceof EmptySymbol ? null : $callback;
        list($operator, $count) = $relationship->getNormalizedExpectedOperation();

        return $this->builder->has($rule->column, $operator, $count, $this->boolean, $callback);
    }

    protected function buildSolo(SoloSymbol $solo)
    {
        /** @var ColumnRule $rule */
        $rule = $solo->rule;

        if ($rule && $rule->boolean && $rule->date) {
            return $this->builder->whereNull($rule->qualifyColumn($this->builder), $this->boolean, ! $solo->negated);
        }

        if ($rule && $rule->boolean) {
            return $this->builder->where($rule->qualifyColumn($this->builder), '=', ! $solo->negated, $this->boolean);
        }

        return $this->buildSearch($solo);
    }

    protected function buildSearch(SoloSymbol $solo)
    {
        $wheres = $this->manager->getSearchables()->map(function ($column) use ($solo) {
            return $this->buildSearchWhereClause($solo, $column);
        });

        if ($wheres->isEmpty()) {
            return;
        }

        if ($wheres->count() === 1) {
            $where = $wheres->first();
            return $this->builder->where($where[0], $where[1], $where[2], $this->boolean);
        }

        return $this->builder->where($wheres->toArray(), null, null, $this->boolean);
    }

    protected function buildSearchWhereClause(SoloSymbol $solo, $column): array
    {
        $boolean = $solo->negated ? 'and' : 'or';
        $operator = $solo->negated ? 'not like' : 'like';
        $content = $solo->content;
        $qualifiedColumn = SearchStringManager::qualifyColumn($this->builder, $column);
        $isCaseInsensitive = $this->manager->getOptions()->get('case_insensitive', false);

        if ($isCaseInsensitive) {
            $content = mb_strtolower($content, 'UTF8');
            $qualifiedColumn = DB::raw("LOWER($qualifiedColumn)");
        }

        return [$qualifiedColumn, $operator, "%$content%", $boolean];
    }

    protected function buildQuery(QuerySymbol $query)
    {
        /** @var ColumnRule $rule */
        if (! $rule = $query->rule) {
            return;
        }

        $query->value = $this->mapValue($query->value, $rule);

        if ($rule->date) {
            return $this->buildDate($query, $rule);
        }

        return $this->buildBasicQuery($query, $rule);
    }

    protected function buildList(ListSymbol $list)
    {
        /** @var ColumnRule $rule */
        if (! $rule = $list->rule) {
            return;
        }

        $column = $rule->qualifyColumn($this->builder);
        $list->values = $this->mapValue($list->values, $rule);

        return $this->builder->whereIn($column, $list->values, $this->boolean, $list->negated);
    }

    protected function buildDate(QuerySymbol $query, ColumnRule $rule)
    {
        $dateWithPrecision = new DateWithPrecision($query->value);

        if (! $dateWithPrecision->carbon) {
            return $this->buildBasicQuery($query, $rule);
        }

        if (in_array($dateWithPrecision->precision, ['micro', 'second'])) {
            $query->value = $dateWithPrecision->carbon;
            return $this->buildBasicQuery($query, $rule);
        }

        list($start, $end) = $dateWithPrecision->getRange();

        if (in_array($query->operator, ['>', '<', '>=', '<='])) {
            $query->value = in_array($query->operator, ['<', '>=']) ? $start : $end;
            return $this->buildBasicQuery($query, $rule);
        }

        return $this->buildDateRange($query, $start, $end, $rule);
    }

    protected function buildDateRange(QuerySymbol $query, $start, $end, ColumnRule $rule)
    {
        $column = $rule->qualifyColumn($this->builder);
        $exclude = in_array($query->operator, ['!=', 'not in']);

        return $this->builder->where([
            [$column, ($exclude ? '<' : '>='), $start, $this->boolean],
            [$column, ($exclude ? '>' : '<='), $end, $this->boolean],
        ], null, null, $this->boolean);
    }

    protected function buildBasicQuery(QuerySymbol $query, ColumnRule $rule)
    {
        $column = $rule->qualifyColumn($this->builder);

        return $this->builder->where($column, $query->operator, $query->value, $this->boolean);
    }

    protected function mapValue($value, ColumnRule $rule)
    {
        if (is_array($value)) {
            return array_map(function ($value) use ($rule) {
                return $rule->map->has($value) ? $rule->map->get($value) : $value;
            }, $value);
        }

        if ($rule->map->has($value)) {
            return $rule->map->get($value);
        }

        if (is_numeric($value)) {
            return $value + 0;
        }

        return $value;
    }
}

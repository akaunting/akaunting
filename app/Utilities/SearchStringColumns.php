<?php

namespace App\Utilities;

use Lorisleiva\LaravelSearchString\AST\ListSymbol;
use Lorisleiva\LaravelSearchString\AST\QuerySymbol;
use Lorisleiva\LaravelSearchString\Options\ColumnRule;
use Lorisleiva\LaravelSearchString\Visitors\BuildColumnsVisitor;

/**
 * Builds the search string's column terms as the package does, except that a negated term also keeps the rows whose
 * column is empty. SQL never matches NULL with != or NOT IN, so "not contact_id:5" dropped every transaction saved
 * without a contact, and a report's "is 5" plus "is not 5" came to less than its total. The negated term sits in a
 * nested where, so a global scope that looks for a top-level column, as the recurring scopes look for type, no
 * longer sees it.
 */
class SearchStringColumns extends BuildColumnsVisitor
{
    protected function buildList(ListSymbol $list)
    {
        if (! $list->negated || ! $list->rule) {
            return parent::buildList($list);
        }

        $column = $list->rule->qualifyColumn($this->builder);
        $values = $this->mapValue($list->values, $list->rule);

        return $this->builder->where(
            fn ($query) => $query->whereNotIn($column, $values)->orWhereNull($column),
            null,
            null,
            $this->boolean,
        );
    }

    protected function buildBasicQuery(QuerySymbol $query, ColumnRule $rule)
    {
        // "not column:NULL" asks for the rows that have a value (IS NOT NULL), as the item picker's
        // "not sale_price:NULL" does
        if (($query->operator != '!=') || is_null($query->value)) {
            return parent::buildBasicQuery($query, $rule);
        }

        $column = $rule->qualifyColumn($this->builder);

        return $this->builder->where(
            fn ($builder) => $builder->where($column, '!=', $query->value)->orWhereNull($column),
            null,
            null,
            $this->boolean,
        );
    }
}

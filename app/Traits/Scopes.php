<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Scopes
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function applyNotRecurringScope(Builder $builder, Model $model)
    {
        // Skip if recurring already in query
        if ($this->scopeValueExists($builder, 'type', '-recurring')) {
            return;
        }

        // Apply not recurring scope
        $builder->isNotRecurring();
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function applyNotSplitScope(Builder $builder, Model $model)
    {
        // Skip if split already in query
        if ($this->scopeValueExists($builder, 'type', '-split')) {
            return;
        }

        // Apply not split scope
        $builder->isNotSplit();
    }

    /**
     * Check if scope exists.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  $column
     * @return boolean
     */
    public function scopeColumnExists($builder, $column)
    {
        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {
            if (empty($where) || empty($where['column'])) {
                continue;
            }

            if (strstr($where['column'], '.')) {
                $whr = explode('.', $where['column']);

                $where['column'] = $whr[1];
            }

            if ($where['column'] != $column) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * Check if scope has the exact value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  $column
     * @param  $value
     * @return boolean
     */
    public function scopeValueExists($builder, $column, $value)
    {
        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {
            if (empty($where) || empty($where['column']) || empty($where['value'])) {
                continue;
            }

            if (strstr($where['column'], '.')) {
                $whr = explode('.', $where['column']);

                $where['column'] = $whr[1];
            }

            if ($where['column'] != $column) {
                continue;
            }

            if (! Str::endsWith($where['value'], $value)) {
                continue;
            }

            return true;
        }

        return false;
    }

    // @deprecated version 3.0.0
    public function scopeExists($builder, $column)
    {
        return $this->scopeColumnExists($builder, $column);
    }
}

<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
        // Skip if recurring is explicitly set
        if ($this->scopeEquals($builder, 'type', 'like', '%-recurring')) {
            return;
        }

        // Skip if scope is already applied
        if ($this->scopeEquals($builder, 'type', 'not like', '%-recurring')) {
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
        // Skip if split is explicitly set
        if ($this->scopeEquals($builder, 'type', 'like', '%-split')) {
            return;
        }

        // Skip if scope is already applied
        if ($this->scopeEquals($builder, 'type', 'not like', '%-split')) {
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
    public function scopeExists($builder, $column)
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
     * @return boolean
     */
    public function scopeEquals($builder, $column, $operator, $value)
    {
        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {
            if (empty($where) || empty($where['column']) || empty($where['operator']) || empty($where['value'])) {
                continue;
            }

            if (strstr($where['column'], '.')) {
                $whr = explode('.', $where['column']);

                $where['column'] = $whr[1];
            }

            if ($where['column'] != $column) {
                continue;
            }

            if ($where['operator'] != $operator) {
                continue;
            }

            if ($where['value'] != $value) {
                continue;
            }

            return true;
        }

        return false;
    }
}

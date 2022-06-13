<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Scopes
{
    public function applyNotRecurringScope(Builder $builder, Model $model): void
    {
        // Skip if type already set
        if ($this->scopeColumnExists($builder, $model->getTable(), 'type')) {
            return;
        }

        // Apply not recurring scope
        $builder->isNotRecurring();
    }

    public function applyNotSplitScope(Builder $builder, Model $model): void
    {
        // Skip if type already set
        if ($this->scopeColumnExists($builder, $model->getTable(), 'type')) {
            return;
        }

        // Apply not split scope
        $builder->isNotSplit();
    }

    public function scopeColumnExists(Builder $builder, string $table, string $column): bool
    {
        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {
            if (empty($where) || empty($where['column'])) {
                continue;
            }

            if (strstr($where['column'], '.')) {
                $whr = explode('.', $where['column']);

                $where['table'] = $whr[0];
                $where['column'] = $whr[1];
            }

            if (! empty($where['table']) && ! empty($table) && ($where['table'] != $table)) {
                continue;
            }

            if ($where['column'] != $column) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function scopeValueExists(Builder $builder, string $table, string $column, string $value): bool
    {
        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {
            if (empty($where) || empty($where['column']) || empty($where['value'])) {
                continue;
            }

            if (strstr($where['column'], '.')) {
                $whr = explode('.', $where['column']);

                $where['table'] = $whr[0];
                $where['column'] = $whr[1];
            }

            if (! empty($where['table']) && ! empty($table) && ($where['table'] != $table)) {
                continue;
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
    public function scopeExists($builder, $column): bool
    {
        return $this->scopeColumnExists($builder, '', $column);
    }
}

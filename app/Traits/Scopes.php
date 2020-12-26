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
    public function applyTypeScope(Builder $builder, Model $model)
    {
        // Skip if already exists
        if ($this->scopeExists($builder, 'type')) {
            return;
        }

        $type = request()->get('type') ?: $this->getTypeFromUrl();

        // Apply type scope
        $builder->where($model->getTable() . '.type', '=', $type);
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

    public function getTypeFromUrl()
    {
        $type = Str::singular(request()->segment(2, ''));

        if ($type == 'revenue') {
            $type = 'income';
        }

        if ($type == 'payment') {
            $type = 'expense';
        }

        return $type;
    }
}

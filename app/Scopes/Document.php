<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Str;

class Document implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // Skip if already exists
        if ($this->exists($builder, 'type')) {
            return;
        }

        $type = Str::singular(request()->segment(2, ''));

        // Apply document scope
        $builder->where($model->getTable() . '.type', '=', $type);
    }

    /**
     * Check if scope exists.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  $column
     * @return boolean
     */
    protected function exists($builder, $column)
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
}

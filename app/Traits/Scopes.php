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
        // Getting type from request causes lots of issues
        // @todo Try event/listener similar to Permissions trait
        return;

        // Skip if already exists
        if ($this->scopeExists($builder, 'type')) {
            return;
        }

        // No request in console
        if (app()->runningInConsole()) {
            return;
        }

        $type = $this->getTypeFromRequest();

        if (empty($type)) {
            return;
        }

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

    public function getTypeFromRequest()
    {
        $type = '';
        $request = request();

        // Skip type scope in dashboard and reports
        if ($request->routeIs('dashboards.*') || $request->routeIs('reports.*')) {
            return $type;
        }

        $type = $request->get('type') ?: Str::singular((string) $request->segment(3));

        if ($type == 'revenue') {
            $type = 'income';
        }

        if ($type == 'payment') {
            $type = 'expense';
        }

        return $type;
    }
}

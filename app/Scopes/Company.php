<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Company implements Scope
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
        $table = $model->getTable();

        // Skip for specific tables
        $skip_tables = [
            'companies', 'jobs', 'firewall_ips', 'firewall_logs', 'media', 'mediables', 'migrations', 'notifications',
            'permissions', 'roles', 'role_companies', 'role_permissions', 'sessions', 'users', 'user_companies',
            'user_dashboards', 'user_permissions', 'user_roles',
        ];

        if (in_array($table, $skip_tables)) {
            return;
        }

        // Skip if already exists
        if ($this->exists($builder, 'company_id')) {
            return;
        }

        // Apply company scope
        $builder->where($table . '.company_id', '=', session('company_id'));
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

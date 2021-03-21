<?php

namespace Lorisleiva\LaravelSearchString\Tests\Concerns;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait DumpsSql
{
    /**
     * @param EloquentBuilder|QueryBuilder $builder
     * @return string
     */
    public function dumpSql($builder): string
    {
        $query = str_replace('?', '%s', $builder->toSql());

        $bindings = collect($builder->getBindings())->map(function ($binding) {
            if (is_string($binding)) return "'$binding'";
            if (is_bool($binding)) return $binding ? 'true' : 'false';
            return $binding;
        })->toArray();

        return str_replace('`', '', vsprintf($query, $bindings));
    }

    /**
     * @param EloquentBuilder|QueryBuilder $builder
     * @return string
     */
    public function dumpSqlWhereClauses($builder): string
    {
        return preg_replace('/select \* from [\w.]+ where (.*)/', '$1', $this->dumpSql($builder));
    }

    /**
     * @param string $input
     * @param string $expected
     * @param null $model
     */
    public function assertSqlEquals(string $input, string $expected, $model = null)
    {
        $this->assertEquals($expected, $this->dumpSql($this->build($input, $model)));
    }

    /**
     * @param string $input
     * @param string $expected
     * @param null $model
     */
    public function assertWhereSqlEquals(string $input, string $expected, $model = null)
    {
        $this->assertEquals($expected, $this->dumpSqlWhereClauses($this->build($input, $model)));
    }
}

<?php

namespace Lorisleiva\LaravelSearchString\Tests\Concerns;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait DumpsWhereClauses
{
    /**
     * @param EloquentBuilder|QueryBuilder $query
     * @return array
     */
    public function dumpWhereClauses($query): array
    {
        if ($query instanceof EloquentBuilder) {
            $query = $query->getQuery();
        }

        return collect($query->wheres)->mapWithKeys(function ($where, $i){
            $where = (object) $where;
            $key = "$where->type[{$where->boolean}][$i]";

            if (isset($where->query)) {
                $children = $this->dumpWhereClauses($where->query);
                return [$key => $children];
            }

            $value = $where->value ?? $where->values ?? null;
            $value = is_array($value) ? ('[' . implode(', ', $value) . ']') : $value;
            $value = is_bool($value) ? ($value ? 'true' : 'false') : $value;
            $value = isset($where->operator) ? "$where->operator $value" : $value;
            return [$key => is_null($value) ? $where->column : "$where->column $value"];
        })->toArray();
    }

    /**
     * @param $input
     * @param array $expected
     * @param null $model
     */
    public function assertWhereClauses($input, array $expected, $model = null)
    {
        $wheres = $this->dumpWhereClauses($this->getBuilder($input, $model));
        $this->assertEquals($expected, $wheres);
    }
}

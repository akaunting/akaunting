<?php

namespace Znck\Eloquent\Traits;

trait HasTableAlias
{
    /**
     * Qualify the given column name by the model's table.
     *
     * @param string $column
     * @return string
     */
    public function qualifyColumn($column)
    {
        if (str_contains($column, '.')) {
            return $column;
        }

        $table = $this->getTable();

        if (str_contains($table, ' as ')) {
            $table = explode(' as ', $table)[1];
        }

        return $table.'.'.$column;
    }
}

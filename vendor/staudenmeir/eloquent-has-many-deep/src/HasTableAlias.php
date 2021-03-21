<?php

namespace Staudenmeir\EloquentHasManyDeep;

use Illuminate\Support\Str;

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
        if (Str::contains($column, '.')) {
            return $column;
        }

        $table = $this->getTable();

        if (Str::contains($table, ' as ')) {
            $table = explode(' as ', $table)[1];
        }

        return $table.'.'.$column;
    }

    /**
     * Set an alias for the model's table.
     *
     * @param string $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        return $this->setTable($this->getTable().' as '.$alias);
    }
}

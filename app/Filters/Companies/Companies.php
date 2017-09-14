<?php

namespace App\Filters\Companies;

use EloquentFilter\ModelFilter;

class Companies extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relatedModel => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function search($query)
    {
        $this->related('settings','settings.key', '=',"'company_name'");
        return $this->related('settings','settings.value', 'LIKE',"'%" . $query . "%'");
    }
}
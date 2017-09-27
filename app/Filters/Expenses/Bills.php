<?php

namespace App\Filters\Expenses;

use EloquentFilter\ModelFilter;

class Bills extends ModelFilter
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
        return $this->whereLike('vendor_name', $query);
    }

    public function vendor($vendor)
    {
        return $this->where('vendor_id', $vendor);
    }

    public function status($status)
    {
        return $this->where('bill_status_code', $status);
    }
}
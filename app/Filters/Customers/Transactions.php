<?php

namespace App\Filters\Customers;

use EloquentFilter\ModelFilter;

class Transactions extends ModelFilter
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
        return $this->whereLike('payment.name', $query)->whereLike('revenue.name', $query);
    }
}
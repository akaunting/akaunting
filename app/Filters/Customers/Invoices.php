<?php

namespace App\Filters\Customers;

use EloquentFilter\ModelFilter;

class Invoices extends ModelFilter
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
        return $this->whereLike('customer_name', $query);
    }

    public function status($status)
    {
        return $this->where('invoice_status_code', $status);
    }
}
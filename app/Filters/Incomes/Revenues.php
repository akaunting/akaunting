<?php

namespace App\Filters\Incomes;

use EloquentFilter\ModelFilter;

class Revenues extends ModelFilter
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
        return $this->whereLike('description', $query);
    }

    public function customer($customer)
    {
        return $this->where('customer_id', $customer);
    }

    public function category($category)
    {
        return $this->where('category_id', $category);
    }

    public function account($account)
    {
        return $this->where('account_id', $account);
    }
}
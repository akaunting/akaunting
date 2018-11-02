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

    public function vendors($vendors)
    {
        return $this->whereIn('vendor_id', (array) $vendors);
    }

    public function categories($categories)
    {
        return $this->whereIn('category_id', (array) $categories);
    }

    public function statuses($statuses)
    {
        return $this->whereIn('bill_status_code', (array) $statuses);
    }

    public function billDate($date)
    {
        $dates = explode('_', $date);
        $dates[0] .= ' 00:00:00';
        $dates[1] .= ' 23:59:59';

        return $this->whereBetween('billed_at', $dates);
    }
}
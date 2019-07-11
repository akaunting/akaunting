<?php

namespace App\Filters\Banking;

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
    
    public function accounts($accounts)
    {
        return $this->whereIn('account_id', (array) $accounts);
    }
    
    public function categories($categories)
    {
        // No category for bills/invoices
        if (in_array($this->getModel()->getTable(), ['bill_payments', 'invoice_payments'])) {
            return $this;
        }

        return $this->whereIn('category_id', (array) $categories);
    }

    public function date($date)
    {
        $dates = explode('_', $date);
        $dates[0] .= ' 00:00:00';
        $dates[1] .= ' 23:59:59';

        return $this->whereBetween('paid_at', $dates);
    }
}
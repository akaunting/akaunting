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
    
    public function account($account_id)
    {
        return $this->where('account_id', $account_id);
    }
    
    public function category($category_id)
    {
        // No category for bills/invoices
        if (in_array($this->getModel()->getTable(), ['bill_payments', 'invoice_payments'])) {
            return $this;
        }

        return $this->where('category_id', $category_id);
    }
}
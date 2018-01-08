<?php

namespace App\Filters\Banking;

use EloquentFilter\ModelFilter;

class Transfers extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relatedModel => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function fromAccount($account_id)
    {
        return $this->where('payments.account_id', $account_id);
    }

    public function toAccount($account_id)
    {
        return $this->related('revenue', 'revenues.account_id', '=', $account_id);
    }
}

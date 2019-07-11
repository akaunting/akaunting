<?php

namespace App\Filters\Banking;

use EloquentFilter\ModelFilter;

class Reconciliations extends ModelFilter
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
}
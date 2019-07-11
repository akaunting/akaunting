<?php

namespace App\Filters\Common;

use EloquentFilter\ModelFilter;

class Items extends ModelFilter
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
        $model = $this->where('name', 'LIKE', '%' . $query . '%');

        $or_fields = ['sku', 'description'];
        foreach ($or_fields as $or_field) {
            $model->orWhere($or_field, 'LIKE', '%' . $query . '%');
        }

        return $model;
    }

    public function categories($ids)
    {
        return $this->whereIn('category_id', (array) $ids);
    }
}

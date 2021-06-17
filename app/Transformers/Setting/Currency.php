<?php

namespace App\Transformers\Setting;

use App\Models\Setting\Currency as Model;
use League\Fractal\TransformerAbstract;

class Currency extends TransformerAbstract
{
    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'name' => $model->name,
            'code' => $model->code,
            'rate' => $model->rate,
            'enabled' => $model->enabled,
            'precision' => $model->precision,
            'symbol' => $model->symbol,
            'symbol_first' => $model->symbol_first,
            'decimal_mark' => $model->decimal_mark,
            'thousands_separator' => $model->thousands_separator,
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}

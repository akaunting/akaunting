<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;

class ItemTax extends Model
{
    use Cloneable;

    protected $table = 'item_taxes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'item_id', 'tax_id'];

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item')->withDefault(['name' => trans('general.na')]);
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax')->withDefault(['name' => trans('general.na'), 'rate' => 0]);
    }
}

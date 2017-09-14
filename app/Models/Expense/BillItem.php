<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Traits\Currencies;

class BillItem extends Model
{

    use Currencies;

    protected $table = 'bill_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_id', 'item_id', 'name', 'sku', 'quantity', 'price', 'total', 'tax', 'tax_id'];

    public function bill()
    {
        return $this->belongsTo('App\Models\Expense\Bill');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item\Item');
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax');
    }
}

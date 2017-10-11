<?php

namespace App\Models\Income;

use App\Models\Model;
use App\Traits\DateTime;

class InvoiceTotal extends Model
{
    use DateTime;

    protected $table = 'invoice_totals';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'invoice_id', 'code', 'name', 'amount', 'sort_order'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Income\Invoice');
    }
}

<?php

namespace App\Models\Income;

use App\Models\Model;

class InvoiceStatus extends Model
{

    protected $table = 'invoice_statuses';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'code'];
}

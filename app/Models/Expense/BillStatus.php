<?php

namespace App\Models\Expense;

use App\Models\Model;

class BillStatus extends Model
{

    protected $table = 'bill_statuses';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'code'];
}

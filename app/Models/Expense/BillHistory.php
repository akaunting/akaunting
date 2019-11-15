<?php

namespace App\Models\Expense;

use App\Abstracts\Model;
use App\Traits\Currencies;

class BillHistory extends Model
{

    use Currencies;

    protected $table = 'bill_histories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_id', 'status_code', 'notify', 'description'];

    public function bill()
    {
        return $this->belongsTo('App\Models\Expense\Bill');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Expense\BillStatus', 'status_code', 'code');
    }
}

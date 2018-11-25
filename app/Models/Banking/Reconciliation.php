<?php

namespace App\Models\Banking;

use App\Models\Model;
use Sofa\Eloquence\Eloquence;

class Reconciliation extends Model
{
    use Eloquence;

    protected $table = 'reconciliations';

    protected $dates = ['deleted_at', 'started_at', 'ended_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'account_id', 'started_at', 'ended_at', 'closing_balance', 'reconciled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['created_at', 'account_id', 'started_at', 'ended_at', 'closing_balance', 'reconciled'];

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account');
    }

    /**
     * Convert closing balance to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setClosingBalanceAttribute($value)
    {
        $this->attributes['closing_balance'] = (double) $value;
    }
}

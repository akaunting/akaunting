<?php

namespace App\Models\Banking;

use App\Abstracts\Model;

class Reconciliation extends Model
{
    protected $table = 'reconciliations';

    protected $dates = ['deleted_at', 'started_at', 'ended_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'account_id', 'started_at', 'ended_at', 'closing_balance', 'reconciled'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'closing_balance' => 'double',
        'reconciled' => 'boolean',
    ];

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
}

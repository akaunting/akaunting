<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reconciliation extends Model
{
    use HasFactory;

    protected $table = 'reconciliations';

    protected $dates = ['deleted_at', 'started_at', 'ended_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'account_id', 'started_at', 'ended_at', 'closing_balance', 'reconciled', 'created_by'];

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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Reconciliation::new();
    }
}

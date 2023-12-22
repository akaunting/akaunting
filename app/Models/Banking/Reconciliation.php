<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reconciliation extends Model
{
    use HasFactory;

    protected $table = 'reconciliations';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'account_id', 'started_at', 'ended_at', 'closing_balance', 'transactions', 'reconciled', 'created_from', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'closing_balance'   => 'double',
        'reconciled'        => 'boolean',
        'transactions'      => 'array',
        'deleted_at'        => 'datetime',
        'started_at'        => 'datetime',
        'ended_at'          => 'datetime',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['created_at', 'account_id', 'started_at', 'ended_at', 'opening_balance', 'closing_balance', 'reconciled'];

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account')->withDefault(['name' => trans('general.na')]);
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('reconciliations.edit', $this->id),
            'permission' => 'update-banking-reconciliations',
            'attributes' => [
                'id' => 'index-line-actions-edit-reconciliation-' . $this->id,
            ],
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'reconciliations.destroy',
            'permission' => 'delete-banking-reconciliations',
            'attributes' => [
                'id' => 'index-line-actions-delete-reconciliation-' . $this->id,
            ],
            'model' => $this,
        ];

        return $actions;
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

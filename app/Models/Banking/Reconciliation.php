<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Utilities\Date;
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
     * Scope to the reconciled reconciliations of an account whose period overlaps the one given.
     *
     * A transaction does not keep the reconciliation that cleared it, the one whose period it is in did. So the
     * periods of the reconciled reconciliations of an account can not overlap, or which one cleared a transaction
     * is not known any more, and saving or deleting one of them marks the transactions of the other as not
     * reconciled.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $account_id
     * @param  mixed  $started_at
     * @param  mixed  $ended_at
     * @param  int|null  $except_id  the reconciliation itself, when it is updated
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReconciledOverlapping($query, $account_id, $started_at, $ended_at, $except_id = null)
    {
        return $query->where('account_id', $account_id)
            ->where('reconciled', 1)
            ->where('started_at', '<=', Date::parse($ended_at)->endOfDay()->toDateTimeString())
            ->where('ended_at', '>=', Date::parse($started_at)->startOfDay()->toDateTimeString())
            ->when($except_id, fn ($query) => $query->where('id', '<>', $except_id))
            ->orderBy('started_at');
    }

    /**
     * The period of the reconciliation, as a warning names it.
     *
     * @return string
     */
    public function getPeriodAttribute(): string
    {
        return company_date($this->started_at) . ' - ' . company_date($this->ended_at);
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

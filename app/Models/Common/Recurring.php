<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Traits\Recurring as RecurringTrait;
use Illuminate\Database\Eloquent\Builder;

class Recurring extends Model
{
    use RecurringTrait;

    public const ACTIVE_STATUS = 'active';
    public const END_STATUS = 'ended';
    public const COMPLETE_STATUS = 'completed';

    protected $table = 'recurring';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'recurable_id',
        'recurable_type',
        'frequency',
        'interval',
        'started_at',
        'status',
        'limit_by',
        'limit_count',
        'limit_date',
        'auto_send',
        'created_from',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'auto_send' => 'boolean',
    ];

    /**
     * Get all of the owning recurable models.
     */
    public function recurable()
    {
        return $this->morphTo()->isRecurring();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), '=', static::ACTIVE_STATUS);
    }

    public function scopeEnded(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), '=', static::END_STATUS);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('status'), '=', static::COMPLETE_STATUS);
    }
}

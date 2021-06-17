<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use App\Traits\Recurring as RecurringTrait;

class Recurring extends Model
{
    use RecurringTrait;

    protected $table = 'recurring';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'recurable_id', 'recurable_type', 'frequency', 'interval', 'started_at', 'count'];

    /**
     * Get all of the owning recurable models.
     */
    public function recurable()
    {
        return $this->morphTo();
    }
}

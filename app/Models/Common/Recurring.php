<?php

namespace App\Models\Common;

use App\Models\Model;

class Recurring extends Model
{

    protected $table = 'recurring';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'recurable_id', 'recurable_type', 'frequency', 'interval', 'started_at', 'count'];


    /**
     * Get all of the owning recurrable models.
     */
    public function recurable()
    {
        return $this->morphTo();
    }
}

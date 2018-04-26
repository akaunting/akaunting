<?php

namespace App\Models\Common;

use App\Models\Model;

class Recurring extends Model
{

    protected $table = 'recurrings';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'recurrable_id', 'recurrable_type', 'frequency', 'interval', 'started_at', 'count'];


    /**
     * Get all of the owning recurrable models.
     */
    public function recurrable()
    {
        return $this->morphTo();
    }
}

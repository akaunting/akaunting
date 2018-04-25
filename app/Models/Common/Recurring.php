<?php

namespace App\Models\Common;

use App\Models\Model;

class Recurring extends Model
{

    /**
     * Get all of the owning recurrable models.
     */
    public function recurrable()
    {
        return $this->morphTo();
    }
}

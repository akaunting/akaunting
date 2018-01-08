<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Media as PMedia;

class Media extends PMedia
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

}

<?php

namespace App\Models\Common;

use App\Traits\Tenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Media as BaseMedia;

class Media extends BaseMedia
{
    use SoftDeletes, Tenants;

    protected $dates = ['deleted_at'];

    protected $fillable = ['company_id'];
}

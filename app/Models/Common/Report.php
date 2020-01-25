<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;

class Report extends Model
{
    use Cloneable;

    protected $table = 'reports';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'class', 'name', 'description', 'settings'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'object',
    ];
}

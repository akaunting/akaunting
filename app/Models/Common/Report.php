<?php

namespace App\Models\Common;

use App\Abstracts\Model;

class Report extends Model
{
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

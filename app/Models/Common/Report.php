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
    protected $fillable = ['company_id', 'name', 'description', 'class', 'group', 'period', 'basis', 'chart'];
}

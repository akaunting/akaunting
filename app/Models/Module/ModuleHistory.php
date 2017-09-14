<?php

namespace App\Models\Module;

use App\Models\Model;

class ModuleHistory extends Model
{

    protected $table = 'module_histories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'module_id', 'category', 'version', 'description'];
}

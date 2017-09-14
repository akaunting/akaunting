<?php

namespace App\Models\Module;

use App\Models\Model;
use Sofa\Eloquence\Eloquence;

class Module extends Model
{
    use Eloquence;

    protected $table = 'modules';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'alias', 'status'];
}

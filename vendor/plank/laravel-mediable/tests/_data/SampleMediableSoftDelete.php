<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Mediable;

class SampleMediableSoftDelete extends Model
{
    use Mediable;
    use SoftDeletes;

    protected $table = 'sample_mediables';
    public $rehydrates_media = true;
}

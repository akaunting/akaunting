<?php

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class SampleMediable extends Model
{
    use Mediable;

    public $rehydrates_media = true;
}

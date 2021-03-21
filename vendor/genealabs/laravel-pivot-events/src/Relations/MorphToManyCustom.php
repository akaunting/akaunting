<?php namespace GeneaLabs\LaravelPivotEvents\Relations;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use GeneaLabs\LaravelPivotEvents\Traits\FiresPivotEventsTrait;

class MorphToManyCustom extends MorphToMany
{
    use FiresPivotEventsTrait;
}

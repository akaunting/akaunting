<?php namespace GeneaLabs\LaravelPivotEvents\Relations;

use GeneaLabs\LaravelPivotEvents\Traits\FiresPivotEventsTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToManyCustom extends BelongsToMany
{
    use FiresPivotEventsTrait;
}

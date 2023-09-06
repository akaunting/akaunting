<?php

namespace Plank\Mediable\Tests\Mocks;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

/**
 * @method static self first()
 */
class SampleMediable extends Model
{
    use Mediable;

    public $rehydrates_media = true;
}

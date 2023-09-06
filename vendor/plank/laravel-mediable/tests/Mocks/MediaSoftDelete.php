<?php

namespace Plank\Mediable\Tests\Mocks;

use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Media;

class MediaSoftDelete extends Media
{
    use SoftDeletes;

    protected $table = 'media';
}

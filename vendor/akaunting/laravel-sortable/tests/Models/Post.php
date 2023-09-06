<?php

namespace Akaunting\Sortable\Tests\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sortable;
}

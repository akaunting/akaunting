<?php

namespace Akaunting\Sortable\Tests\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Sortable;

    public $sortable = [
        'id',
        'name',
        'amount',
    ];

    public $sortableAs = [
        'nick_name',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function addressSortable($query, $direction)
    {
        return $query->join('profiles', 'users.id', '=', 'profiles.user_id')->orderBy('address', $direction)->select('users.*');
    }
}

<?php

namespace Lorisleiva\LaravelSearchString\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class User extends Model
{
    use SearchString;

    protected $searchStringColumns = [
        'name' => ['searchable' => true],
        'email' => ['searchable' => true],
        'age',
        'comments' => [
            'key' => '/^comments|writtenComments$/',
            'relationship' => true,
        ],
        'favouriteComments' => ['relationship' => true],
        'favourites' => ['relationship' => true],
        'created_at' => 'date',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favouriteComments()
    {
        return $this->belongsToMany(Comment::class);
    }

    public function favourites()
    {
        return $this->hasMany(CommentUser::class);
    }
}

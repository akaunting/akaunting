<?php

namespace Lorisleiva\LaravelSearchString\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class Product extends Model
{
    use SearchString;

    protected $casts = [
        'paid' => 'boolean',
    ];

    protected $searchStringColumns = [
        'name' => ['searchable' => true],
        'price',
        'description' => ['searchable' => true],
        'paid',         // Automatically marked as boolean.
        'boolean_variable' => ['boolean' => true],
        'created_at',   // Automatically marked as date and boolean.
        'comments' => ['relationship' => true],
    ];

    protected $searchStringKeywords = [
        'order_by' => 'sort',
        'select' => 'fields',
        'limit' => 'limit',
        'offset' => 'from',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

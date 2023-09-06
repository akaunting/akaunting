# Sortable behavior package for Laravel

![Downloads](https://img.shields.io/packagist/dt/akaunting/laravel-sortable)
![Tests](https://img.shields.io/github/actions/workflow/status/akaunting/laravel-sortable/tests.yml?label=tests)
[![StyleCI](https://github.styleci.io/repos/442271942/shield?style=flat&branch=master)](https://styleci.io/repos/442271942)
[![License](https://img.shields.io/github/license/akaunting/laravel-sortable)](LICENSE.md)

This package allows you to add sortable behavior to `models` and `views`. It ships with a trait where you can set the sortable fields and a blade directive to generate table headers automatically.

## Getting Started

### 1. Install

Run the following command:

```bash
composer require akaunting/laravel-sortable
```

### 2. Publish

Publish configuration

```bash
php artisan vendor:publish --tag=sortable
```

### 3. Configure

You can change the column sorting settings of your app from `config/sortable.php` file

## Usage

All you have to do is use the `Sortable` trait inside your model and define the `$sortable` fields.

```php
use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sortable;
    ...

    public $sortable = [
        'id',
        'title',
        'author',
        'created_at',
    ];
    ...
}
```

If you don't define the `$sortable` array, the `Scheme::hasColumn()` function is used which runs an extra database query.

### Scope

The trait adds a `sortable` scope to the model so you can use it just before `paginate`:

```php
public function index()
{
    $posts = Post::query()->sortable()->paginate(10);

    return view('posts.index')->with(['posts' => $posts]);
}
```

You can set also default sorting field which will be applied when URL is empty.

```php
$posts = $post->sortable(['author'])->paginate(10); // $post->orderBy('posts.author', 'asc')

$posts = $post->sortable(['title'])->paginate(10); // $post->orderBy('posts.title', 'asc')

$posts = $post->sortable(['title' => 'desc'])->paginate(10); // $post->orderBy('posts.title', 'desc')
```

### Blade Directive

There is also a `blade` directive for you to create sortable links in your views:

```blade
@sortablelink('title', trans('general.title'), ['parameter' => 'smile'],  ['rel' => 'nofollow'])
```

The *first* parameter is the column in database. The *second* one is displayed inside the anchor tag. The *third* one is an `array()`, and it sets the default (GET) query string. The *fourth* one is also an `array()` for additional anchor-tag attributes. You can use a custom URL as 'href' attribute in the fourth parameter, which will append the query string.

Only the first parameter is required.

Examples:

```blade
@sortablelink('title')
@sortablelink('title', trans('general.title'))
@sortablelink('title', trans('general.title'), ['filter' => 'active, visible'])
@sortablelink('title', trans('general.title'), ['filter' => 'active, visible'], ['class' => 'btn btn-success', 'rel' => 'nofollow', 'href' => route('posts.index')])
```

#### Icon Set

You can use any icon set you want. Just change the `icons.wrapper` from the config file accordingly. By default, it uses Font Awesome.

### Blade Component

Same as the directive, there is also a `blade` component for you to create sortable links in your views:

```html
<x-sortablelink column="title" title="{{ trans('general.title') }}" :query="['parameter' => 'smile']"  :arguments="['rel' => 'nofollow']" />
```

### Sorting Relationships

The package supports `HasOne` and `BelongsTo` relational sorting:

```php
class Post extends Model
{
    use Sortable;
    ...

    protected $fillable = [
        'title',
        'author_id',
        'body',
    ];

    public $sortable = [
        'id',
        'title',
        'author',
        'created_at',
        'updated_at',
    ];

    /**
    * Get the author associated with the post.
    */
    public function author()
    {
        return $this->hasOne(\App\Models\Author::class);
    }
    ...
}
```

And you can use the relation in views:

```blade
// resources/views/posts/index.blade.php

@sortablelink('title', trans('general.title'))
@sortablelink('author.name', trans('general.author'))
```

> **Note**: In case there is a self-referencing model (like comments, categories etc.); parent table will be aliased with `parent_` string.

### Advanced Relation

You can also extend the relation sorting feature by creating a function with `Sortable` suffix. There you're free to write your own queries and apply `orderBy()` manually:

```php
class User extends Model
{
    use Sortable;
    ...

    public $sortable = [
        'name',
        'address',
    ];

    public function addressSortable($query, $direction)
    {
        return $query->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->orderBy('address', $direction)
                    ->select('users.*');
    }
    ...
```

The usage in `controller` and `view` remains the same.

### Aliasing

You can declare the `$sortableAs` array in your model and use it to alias (bypass column exists check), and ignore prefixing with table:

```php
public $sortableAs = [
    'nick_name',
];
```

In controller

```php
$users = $user->select(['name as nick_name'])->sortable(['nick_name'])->paginate(10);
```

In view

```blade
@sortablelink('nick_name', 'nick')
```

It's very useful when you want to sort results using [`withCount()`](https://laravel.com/docs/eloquent-relationships#counting-related-models).

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Pull requests are more than welcome. You must follow the PSR coding standards.

## Security

Please review [our security policy](https://github.com/akaunting/laravel-sortable/security/policy) on how to report security vulnerabilities.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [Martin Kiesel](https://github.com/Kyslik)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.

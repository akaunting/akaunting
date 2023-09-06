![CI](https://github.com/staudenmeir/belongs-to-through/workflows/CI/badge.svg)
[![Code Coverage](https://scrutinizer-ci.com/g/staudenmeir/belongs-to-through/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/staudenmeir/belongs-to-through/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/staudenmeir/belongs-to-through/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/staudenmeir/belongs-to-through/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/staudenmeir/belongs-to-through/v/stable)](https://packagist.org/packages/staudenmeir/belongs-to-through)
[![Total Downloads](https://poser.pugx.org/staudenmeir/belongs-to-through/downloads)](https://packagist.org/packages/staudenmeir/belongs-to-through)
[![License](https://poser.pugx.org/staudenmeir/belongs-to-through/license)](https://packagist.org/packages/staudenmeir/belongs-to-through)

## Introduction
This inverse version of `HasManyThrough` allows `BelongsToThrough` relationships with unlimited intermediate models.

Supports Laravel 5.0+.

## Installation

    composer require staudenmeir/belongs-to-through:"^2.5"

Use this command if you are in PowerShell on Windows (e.g. in VS Code):

    composer require staudenmeir/belongs-to-through:"^^^^2.5"

## Usage

Consider this `HasManyThrough` relationship:  
`Country` → has many → `User` → has many → `Post`

```php
class Country extends Model
{
    public function posts()
    {
        return $this->hasManyThrough(Post::class, User::class);
    }
}
```

Use the `BelongsToThrough` trait in your model to define the inverse relationship:  
`Post` → belongs to → `User` → belongs to → `Country`  

```php
class Post extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    public function country()
    {
        return $this->belongsToThrough(Country::class, User::class);
    }
}
```

You can also define deeper relationships:  
`Comment` → belongs to → `Post` → belongs to → `User` → belongs to → `Country`

Supply an array of intermediate models as the second argument, from the related (`Country`) to the parent model (`Comment`):  

```php
class Comment extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    public function country()
    {
        return $this->belongsToThrough(Country::class, [User::class, Post::class]);
    }
}
```

You can specify custom foreign keys as the fifth argument:

```php
class Comment extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    public function country()
    {
        return $this->belongsToThrough(
            Country::class,
            [User::class, Post::class], 
            null,
            '',
            [User::class => 'custom_user_id']
        );
    }
}
```

### Table Aliases

If your relationship path contains the same model multiple times, you can specify a table alias (Laravel 6+):

```php
class Comment extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    public function grandparent()
    {
        return $this->belongsToThrough(
            Comment::class,
            Comment::class . ' as alias', 
            null,
            '',
            [Comment::class => 'parent_id']
        );
    }
}
```

Use the `HasTableAlias` trait in the models you are aliasing:

```php
class Comment extends Model
{
    use \Znck\Eloquent\Traits\HasTableAlias;
}
```

### Soft Deleting

By default, soft-deleted intermediate models will be excluded from the result. Use `withTrashed()` to include them:

```php
class Comment extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    public function country()
    {
        return $this->belongsToThrough(Country::class, [User::class, Post::class])
            ->withTrashed('users.deleted_at');
    }
}

class User extends Model
{
    use SoftDeletes;
}
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE OF CONDUCT](.github/CODE_OF_CONDUCT.md) for details.

## Credits

- [Rahul Kadyan](https://github.com/znck)
- [Danny Weeks](https://github.com/dannyweeks)
- [All Contributors](../../contributors)

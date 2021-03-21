![CI](https://github.com/staudenmeir/eloquent-has-many-deep/workflows/CI/badge.svg)
[![Code Coverage](https://scrutinizer-ci.com/g/staudenmeir/eloquent-has-many-deep/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/staudenmeir/eloquent-has-many-deep/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/staudenmeir/eloquent-has-many-deep/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/staudenmeir/eloquent-has-many-deep/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/staudenmeir/eloquent-has-many-deep/v/stable)](https://packagist.org/packages/staudenmeir/eloquent-has-many-deep)
[![Total Downloads](https://poser.pugx.org/staudenmeir/eloquent-has-many-deep/downloads)](https://packagist.org/packages/staudenmeir/eloquent-has-many-deep)
[![License](https://poser.pugx.org/staudenmeir/eloquent-has-many-deep/license)](https://packagist.org/packages/staudenmeir/eloquent-has-many-deep)

## Introduction
This extended version of `HasManyThrough` allows relationships with unlimited intermediate models.  
It supports [many-to-many](#manytomany) and [polymorphic](#morphmany) relationships and all their possible combinations.

Supports Laravel 5.5.29+.

## Installation

    composer require staudenmeir/eloquent-has-many-deep:"^1.7"

## Versions

 Laravel | Package
:--------|:----------
 5.5–5.7 | 1.7
 5.8     | 1.8
 6.x     | 1.11
 7.x     | 1.12
 8.x     | 1.13
 
## Usage

- [HasMany](#hasmany)
- [ManyToMany](#manytomany)
- [MorphMany](#morphmany)
- [MorphToMany](#morphtomany)
- [MorphedByMany](#morphedbymany)
- [BelongsTo](#belongsto)
- [Existing Relationships](#existing-relationships)
- [HasOneDeep](#hasonedeep)
- [Intermediate and Pivot Data](#intermediate-and-pivot-data)
- [Table Aliases](#table-aliases)
- [Soft Deleting](#soft-deleting)

The package offers two ways of defining deep relationships:  
You can specify the intermediate models, foreign and local keys manually or concatenate [existing relationships](#existing-relationships).

### HasMany

Consider the [documentation example](https://laravel.com/docs/eloquent-relationships#has-many-through) with an additional level:  
`Country` → has many → `User` → has many → `Post` → has many → `Comment`

```php
class Country extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function comments()
    {
        return $this->hasManyDeep('App\Comment', ['App\User', 'App\Post']);
    }
}
```

Just like with `hasManyThrough()`, the first argument of `hasManyDeep()` is the related model. The second argument is an array of intermediate models, from the far parent (the model where the relationship is defined) to the related model.

By default, `hasManyDeep()` uses the Eloquent conventions for foreign and local keys. You can also specify custom foreign keys as the third argument and custom local keys as the fourth argument: 

```php
class Country extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function comments()
    {
        return $this->hasManyDeep(
            'App\Comment',
            ['App\User', 'App\Post'], // Intermediate models, beginning at the far parent (Country).
            [
               'country_id', // Foreign key on the "users" table.
               'user_id',    // Foreign key on the "posts" table.
               'post_id'     // Foreign key on the "comments" table.
            ],
            [
              'id', // Local key on the "countries" table.
              'id', // Local key on the "users" table.
              'id'  // Local key on the "posts" table.
            ]
        );
    }
}
```

You can use `null` placeholders for default keys:

```php
class Country extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function comments()
    {
        return $this->hasManyDeep('App\Comment', ['App\User', 'App\Post'], [null, 'custom_user_id']);
    }
}
```

### ManyToMany

You can include `ManyToMany` relationships in the intermediate path.

#### ManyToMany → HasMany

Consider the [documentation example](https://laravel.com/docs/eloquent-relationships#many-to-many) with an additional `HasMany` level:  
`User` → many to many → `Role` → has many → `Permission`

Add the pivot table to the intermediate models:

```php
class User extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function permissions()
    {
        return $this->hasManyDeep('App\Permission', ['role_user', 'App\Role']);
    }
}
```

If you specify custom keys, remember to swap the foreign and local key on the "right" side of the pivot table:

```php
class User extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function permissions()
    {
        return $this->hasManyDeep(
            'App\Permission',
            ['role_user', 'App\Role'], // Intermediate models and tables, beginning at the far parent (User).
            [           
               'user_id', // Foreign key on the "role_user" table.
               'id',      // Foreign key on the "roles" table (local key).
               'role_id'  // Foreign key on the "permissions" table.
            ],
            [          
              'id',      // Local key on the "users" table.
              'role_id', // Local key on the "role_user" table (foreign key).
              'id'       // Local key on the "roles" table.
            ]
        );
    }
}
```

#### ManyToMany → ManyToMany

Consider the [documentation example](https://laravel.com/docs/eloquent-relationships#many-to-many) with an additional `ManyToMany` level:  
`User` → many to many → `Role` → many to many → `Permission`

Add the pivot table to the intermediate models:

```php
class User extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function permissions()
    {
        return $this->hasManyDeep('App\Permission', ['role_user', 'App\Role', 'permission_role']);
    }
}
```

### MorphMany

You can include `MorphMany` relationships in the intermediate path.

Consider the [documentation example](https://laravel.com/docs/eloquent-relationships#polymorphic-relations) with an additional level:  
`User` → has many → `Post` → morph many → `Comment`

Specify the polymorphic foreign keys as an array, starting with the `*_type` column:

```php
class User extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function postComments()
    {
        return $this->hasManyDeep(
            'App\Comment',
            ['App\Post'],
            [null, ['commentable_type', 'commentable_id']]
        );
    }
}
```

### MorphToMany

You can include `MorphToMany` relationships in the intermediate path.

Consider the [documentation example](https://laravel.com/docs/eloquent-relationships#many-to-many-polymorphic-relations) with an additional level:    
`User` → has many → `Post` → morph to many → `Tag`

Add the pivot table to the intermediate models and specify the polymorphic foreign keys as an array, starting with the `*_type` column:

```php
class User extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function postTags()
    {
        return $this->hasManyDeep(
            'App\Tag',
            ['App\Post', 'taggables'],
            [null, ['taggable_type', 'taggable_id'], 'id'],
            [null, null, 'tag_id']
        );
    }
}
```

Remember to swap the foreign and local key on the "right" side of the pivot table:

### MorphedByMany

You can include `MorphedByMany` relationships in the intermediate path.

Consider the [documentation example](https://laravel.com/docs/eloquent-relationships#many-to-many-polymorphic-relations) with an additional level:  
`Tag` → morphed by many → `Post` → has many → `Comment`

Add the pivot table to the intermediate models and specify the polymorphic local keys as an array, starting with the `*_type` column:

```php
class Tag extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function postComments()
    {
        return $this->hasManyDeep(
            'App\Comment',
            ['taggables', 'App\Post'],
            [null, 'id'],
            [null, ['taggable_type', 'taggable_id']]
        );
    }
}
```

### BelongsTo

You can include `BelongsTo` relationships in the intermediate path:  
`Tag` → morphed by many → `Post` → belongs to → `User`

Swap the foreign and local key:

```php
class Tag extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function postAuthors()
    {
        return $this->hasManyDeep(
            'App\User',
            ['taggables', 'App\Post'],
            [null, 'id', 'id'],
            [null, ['taggable_type', 'taggable_id'], 'user_id']
        );
    }
}
```

### Existing Relationships

You can also define a `HasManyDeep` relationship by concatenating existing relationships:

```php
class Country extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function comments()
    {
        return $this->hasManyDeepFromRelations($this->posts(), (new Post)->comments());
    }

    public function posts()
    {
        return $this->hasManyThrough('App\Post', 'App\User');
    }
}

class Post extends Model
{
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
```

### HasOneDeep

Define a `HasOneDeep` relationship if you only want to retrieve a single related instance:

```php
class Country extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function latestComment()
    {
        return $this->hasOneDeep('App\Comment', ['App\User', 'App\Post'])
            ->latest('comments.created_at');
    }
}
```

### Intermediate and Pivot Data

Use `withIntermediate()` to retrieve attributes from intermediate tables:

```php
public function comments()
{
    return $this->hasManyDeep('App\Comment', ['App\User', 'App\Post'])
        ->withIntermediate('App\Post');
}

foreach ($country->comments as $comment) {
    // $comment->post->title
}
```

By default, this will retrieve all the table's columns. Be aware that this executes a separate query to get the list of columns.

You can specify the selected columns as the second argument:

```php
public function comments()
{
    return $this->hasManyDeep('App\Comment', ['App\User', 'App\Post'])
        ->withIntermediate('App\Post', ['id', 'title']);
}
```

As the third argument, you can specify a custom accessor:

```php
public function comments()
{
    return $this->hasManyDeep('App\Comment', ['App\User', 'App\Post'])
        ->withIntermediate('App\Post', ['id', 'title'], 'accessor');
}

foreach ($country->comments as $comment) {
    // $comment->accessor->title
}
```

If you retrieve data from multiple tables, you can use nested accessors:

```php
public function comments()
{
    return $this->hasManyDeep('App\Comment', ['App\User', 'App\Post'])
        ->withIntermediate('App\Post')
        ->withIntermediate('App\User', ['*'], 'post.user');
}

foreach ($country->comments as $comment) {
    // $comment->post->title
    // $comment->post->user->name
}
```

Use `withPivot()` for the pivot tables of `BelongsToMany` and `MorphToMany`/`MorphedByMany` relationships:

```php
public function permissions()
{
    return $this->hasManyDeep('App\Permission', ['role_user', 'App\Role'])
        ->withPivot('role_user', ['expires_at']);
}

foreach ($user->permissions as $permission) {
    // $permission->role_user->expires_at
}
```

You can specify a custom pivot model as the third argument and a custom accessor as the fourth: 

```php
public function permissions()
{
    return $this->hasManyDeep('App\Permission', ['role_user', 'App\Role'])
        ->withPivot('role_user', ['expires_at'], 'App\RoleUser', 'pivot');
}

foreach ($user->permissions as $permission) {
    // $permission->pivot->expires_at
}
```

### Table Aliases

If your relationship path contains the same model multiple times, you can specify a table alias:

```php
class Post extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function commentReplies()
    {
        return $this->hasManyDeep('App\Comment', ['App\Comment as alias'], [null, 'parent_id']);
    }
}
```

Use the `HasTableAlias` trait in the models you are aliasing:

```php
class Comment extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasTableAlias;
}
```

For pivot tables, this requires custom models:

```php
class User extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function permissions()
    {
        return $this->hasManyDeep('App\Permission', ['App\RoleUser as alias', 'App\Role']);
    }
}

class RoleUser extends Pivot
{
    use \Staudenmeir\EloquentHasManyDeep\HasTableAlias;
}
```

Use `setAlias()` to specify a table alias when concatenating existing relationships (Laravel 6+):

```php
class Post extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function commentReplies()
    {
        return $this->hasManyDeepFromRelations(
            $this->comments(),
            (new Comment)->setAlias('alias')->replies()
        );
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class Comment extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasTableAlias;

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
```

### Soft Deleting

By default, soft-deleted intermediate models will be excluded from the result. Use `withTrashed()` to include them:

```php
class Country extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    public function comments()
    {
        return $this->hasManyDeep('App\Comment', ['App\User', 'App\Post'])
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

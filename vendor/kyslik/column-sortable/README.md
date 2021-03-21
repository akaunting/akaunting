<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [Column sorting for Laravel 5.5-8](#column-sorting-for-laravel-55-8)
- [Setup](#setup)
  - [Composer](#composer)
    - [Laravel's >=5.5 auto discovery](#laravels-55-auto-discovery)
    - [Manual installation (pre 5.5)](#manual-installation-pre-55)
  - [Publish configuration](#publish-configuration)
- [Usage](#usage)
  - [Blade Extension](#blade-extension)
  - [Configuration in few words](#configuration-in-few-words)
  - [Font Awesome (default font classes)](#font-awesome-default-font-classes)
    - [Font Awesome 5](#font-awesome-5)
  - [Full Example](#full-example)
    - [Routes](#routes)
    - [Controller's `index()` method](#controllers-index-method)
    - [View (_pagination included_)](#view-pagination-included)
- [HasOne / BelongsTo Relation sorting](#hasone--belongsto-relation-sorting)
  - [Define hasOne relation](#define-hasone-relation)
  - [Define belongsTo relation](#define-belongsto-relation)
  - [Define `$sortable` arrays](#define-sortable-arrays)
  - [Blade and relation sorting](#blade-and-relation-sorting)
- [ColumnSortable overriding (advanced)](#columnsortable-overriding-advanced)
- [Aliasing](#aliasing)
  - [Using `withCount()`](#using-withcount)
- [Exception to catch](#exception-to-catch)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

# Column sorting for Laravel 5.5-8

[![Latest Version](https://img.shields.io/github/release/Kyslik/column-sortable.svg?style=flat-square)](https://github.com/Kyslik/column-sortable/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/Kyslik/column-sortable.svg?style=flat-square)](https://packagist.org/packages/Kyslik/column-sortable)
![run-tests](https://github.com/Kyslik/column-sortable/workflows/run-tests/badge.svg)

Package for handling column sorting in Laravel 5.[5-8]. For earlier versions of Laravel checkout branch [L5.1-3](https://github.com/Kyslik/column-sortable/tree/L5.1-3)

# Setup

## Composer

Pull this package in through Composer (development/latest version `dev-master`)

```json
{
    "require": {
        "kyslik/column-sortable": "^6.0"
    }
}
```

```sh
composer update
```

### Laravel's >=5.5 auto discovery

Simply install the package and let Laravel do its magic.

>**Note (pre Laravel 6.0)**: : major and minor versions should match with Laravel's version, for example if you are using Laravel 5.4, column-sortable version should be `5.4.*`.

### Manual installation (pre 5.5)

Add the service provider to array of providers in `config/app.php`

```php
'providers' => [

    App\Providers\RouteServiceProvider::class,

    /*
     * Third Party Service Providers...
     */
    Kyslik\ColumnSortable\ColumnSortableServiceProvider::class,
],
```

## Publish configuration

Publish the package configuration file to your application.

```sh
php artisan vendor:publish --provider="Kyslik\ColumnSortable\ColumnSortableServiceProvider" --tag="config"
```

See configuration file [(`config/columnsortable.php`)](https://github.com/Kyslik/column-sortable/blob/master/src/config/columnsortable.php) yourself and make adjustments as you wish.

# Usage

Use **Sortable** trait inside your *Eloquent* model(s). Define `$sortable` array (see example code below).

>**Note**: `Scheme::hasColumn()` is run only when `$sortable` is not defined - less DB hits per request.

```php
use Kyslik\ColumnSortable\Sortable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Sortable;
    ...

    public $sortable = ['id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'];
    ...
}
```

You're set to go.

**Sortable** trait adds Sortable scope to the models so you can use it with paginate.

## Blade Extension

There is a blade extension for you to use **@sortablelink()**

```blade
@sortablelink('column', 'Title', ['parameter' => 'smile'],  ['rel' => 'nofollow'])
```

**Column** (1st) parameter is column in database, **Title** (2nd) parameter is displayed inside anchor tags, `array()` parameter (3rd) is default (GET) query strings parameter and `array()` parameter (4th) is for additional anchor-tag attributes.  

You can omit 2nd, 3rd and 4th parameter.

Possible examples and usages of blade extension:

```blade
@sortablelink('name')
@sortablelink('name', 'Username')
@sortablelink('address', trans('fields.address'), ['filter' => 'active, visible'])
@sortablelink('address', trans('fields.address'), ['filter' => 'active, visible'], ['class' => 'btn btn-block', 'rel' => 'nofollow'])
```

If you do not fill **Title** (2nd parameter) column name is used instead.

>**Note**: you can set default formatting function that is applied on **Title** (2nd parameter), by default this is set to [`ucfirst`](http://php.net/manual/en/function.ucfirst.php).

## Configuration in few words

**Sortablelink** blade extension distinguishes between *types* (**numeric**, **amount** and **alpha**) and applies different class for each of them.  

See following snippet:

```php
'columns' => [
    'numeric'  => [
        'rows' => ['created_at', 'updated_at', 'level', 'id'],
        'class' => 'fa fa-sort-numeric'
    ],
    'amount'   => [
        'rows' => ['price'],
        'class' => 'fa fa-sort-amount'
    ],
    'alpha'    => [
        'rows' => ['name', 'description', 'email', 'slug'],
        'class' => 'fa fa-sort-alpha',
    ],
],
```

Rest of the [config file](https://github.com/Kyslik/column-sortable/blob/master/src/config/columnsortable.php) should be crystal clear and I advise you to skim it.

## Font Awesome (default font classes)

Install [Font-Awesome](https://fontawesome.com/v4.7.0/) for visual [Joy](http://pixar.wikia.com/wiki/Joy). Search "sort" in [cheatsheet](https://fontawesome.com/v4.7.0/icons/) and see used icons (12) yourself.

### Font Awesome 5

Change the suffix class in the [config file](https://github.com/Kyslik/column-sortable/blob/master/src/config/columnsortable.php) from `-asc`/`-desc` (FA 4) to `-up`/`-down` (FA 5) respectively.

 ```php
/* this is FA 5 compatible.
suffix class that is appended when ascending direction is applied */
'asc_suffix'                    => '-up',

/* suffix class that is appended when descending direction is applied */
'desc_suffix'                   => '-down',
```

> **Note**: If you haven't published the config yet, follow the [instructions above](#publish-configuration).

## Full Example

You may be interested in [working example repository](https://github.com/Kyslik/column-sortable-example), where package usage is demonstrated.

### Routes

```php
Route::get('users', ['as' => 'users.index', 'uses' => 'HomeController@index']);
```

### Controller's `index()` method

```php
public function index(User $user)
{
    $users = $user->sortable()->paginate(10);

    return view('user.index')->withUsers($users);
}
```

You can set default sorting parameters which will be applied when URL is empty.

>**For example**: page is loaded for first time, default direction is [configurable](https://github.com/Kyslik/column-sortable/blob/master/src/config/columnsortable.php#L77) (asc)

```php
$users = $user->sortable('name')->paginate(10);
// produces ->orderBy('users.name', 'asc')

$users = $user->sortable(['name'])->paginate(10); 
// produces ->orderBy('users.name', 'asc')

$users = $user->sortable(['name' => 'desc'])->paginate(10);
// produces ->orderBy('users.name', 'desc')
```

### View (_pagination included_)

```blade
@sortablelink('id', 'Id')
@sortablelink('name')

@foreach ($users as $user)
    {{ $user->name }}
@endforeach
{!! $users->appends(\Request::except('page'))->render() !!}
```

>**Note**: Blade's ability to recognize directives depends on having space before directive itself `<tr> @sortablelink('Name')`

# HasOne / BelongsTo Relation sorting

## Define hasOne relation

In order to make relation sorting work, you have to define **hasOne()** relation in your model.

```php
/**
* Get the user_detail record associated with the user.
*/
public function detail()
{
    return $this->hasOne(App\UserDetail::class);
}
```

## Define belongsTo relation

>**Note**: in case there is a self-referencing model (like comments, categories etc.); parent table will be aliased with `parent_` string, for more information see [issue #60](https://github.com/Kyslik/column-sortable/issues/60).

```php
/**
 * Get the user that owns the phone.
 */
public function user()
{
    return $this->belongsTo(App\User::class);
}
```

In *User* model we define **hasOne** relation to *UserDetail* model (which holds phone number and address details).

## Define `$sortable` arrays

Define `$sortable` array in both models (else, package uses `Scheme::hasColumn()` which is an extra database query).

for *User*

```php
public $sortable = ['id', 'name', 'email', 'created_at', 'updated_at'];
```

for *UserDetail*

```php
public $sortable = ['address', 'phone_number'];
```

## Blade and relation sorting

In order to tell package to sort using relation:

```blade
@sortablelink('detail.phone_number', 'phone')
@sortablelink('user.name', 'name')
```

>**Note**: package works with relation "name" (method) that you define in model instead of table name.

>**WARNING**: do not use combination of two different relations at the same time, you are going to get errors that relation is not defined

In config file you can set your own separator in case `.` (dot) is not what you want.

```php
'uri_relation_column_separator' => '.'
```

# ColumnSortable overriding (advanced)

It is possible to override ColumnSortable relation feature, basically you can write your own join(s) / queries and apply `orderBy()` manually.

See example:

```php
class User extends Model
{
    use Sortable;

    public $sortable = ['name'];
    ...

    public function addressSortable($query, $direction)
    {
        return $query->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->orderBy('address', $direction)
                    ->select('users.*');
    }
    ...
```

Controller is the same `$users = $user->sortable()->paginate(10);`

In view just use `@sortablelink('address')`

>Huge thanks to @neutralrockets and his comments on [#8](https://github.com/Kyslik/column-sortable/issues/8). Another example on how to use overriding is issue [#41](https://github.com/Kyslik/column-sortable/issues/41#issuecomment-250895909).

# Aliasing

It is possible to declare `$sortableAs` array and use it to alias (bypass column exists check), and ignore prefixing with table.

In model

```php
...
$sortableAs = ['nick_name'];
...
```

In controller

```php
$users = $user->select(['name as nick_name'])->sortable(['nick_name'])->paginate(10);
```

In view

```blade
@sortablelink('nick_name', 'nick')
```

See [#44](https://github.com/Kyslik/column-sortable/issues/44) for more information on aliasing.

## Using `withCount()`

Aliasing is useful when you want to sort results with [`withCount()`](https://laravel.com/docs/5.8/eloquent-relationships#counting-related-models), see [issue #49](https://github.com/Kyslik/column-sortable/issues/49) for more information.

# Exception to catch

Package throws custom exception `ColumnSortableException` with three codes (0, 1, 2).

Code **0** means that `explode()` fails to explode URI parameter "sort" in to two values.
For example: `sort=detail..phone_number` - produces array with size of 3, which causes package to throw exception with code **0**.

Code **1** means that `$query->getRelation()` method fails, that means when relation name is invalid (does not exists, is not declared in model).

Code **2** means that provided relation through sort argument is not instance of **hasOne**.

Example how to catch:

```php
...
try {
    $users = $user->with('detail')->sortable(['detail.phone_number'])->paginate(5);
} catch (\Kyslik\ColumnSortable\Exceptions\ColumnSortableException $e) {
    dd($e);
}
```

>**Note**: I strongly recommend to catch **ColumnSortableException** because there is a user input in question (GET parameter) and any user can modify it in such way that package throws ColumnSortableException with code `0`.

# Laravel Pivot Events
This package introduces new eloquent events for sync(), attach(), detach(), or
updateExistingPivot() methods on BelongsToMany and MorphToMany relationships.

This package is a fork of [fico7489/laravel-pivot](https://github.com/fico7489/laravel-pivot)
created mainly to address compatibility issues with
[Laravel Telescope](https://github.com/laravel/telescope) and
[Model Caching for Laravel](https://github.com/GeneaLabs/laravel-model-caching).

## Sponsors
We thank the following sponsors for their generosity. Please take a moment to check them out:

- [LIX](https://lix-it.com)

## Requirements
- Laravel 8.0+
- PHP 7.3+

## Installation
1.Install package with composer:
    ```
    composer require "genealabs/laravel-pivot-events:*"
    ```

2. Use `GeneaLabs\LaravelPivotEvents\Traits\PivotEventTrait` trait in your base
   model or only in particular models.
    ```php
    // ...
    use GeneaLabs\LaravelPivotEvents\Traits\PivotEventTrait;
    use Illuminate\Database\Eloquent\Model;

    abstract class BaseModel extends Model
    {
        use PivotEventTrait;
        // ...
    }
    ```

## New Eloquent Events

You can check all eloquent events here:  https://laravel.com/docs/5.8/eloquent#events)

New events are :
- `pivotSyncing`, `pivotSynced`
- `pivotAttaching`, `pivotAttached`
- `pivotDetaching`, `pivotDetached`
- `pivotUpdating`, `pivotUpdated`

The easiest way to catch events is using methods in your model's `boot()` method:
```php
public static function boot()
{
    parent::boot();

    static::pivotSyncing(function ($model, $relationName) {
        //
    });
     
    static::pivotSynced(function ($model, $relationName, $changes) {
        //
    });

    static::pivotAttaching(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
        //
    });
    
    static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
        //
    });
    
    static::pivotDetaching(function ($model, $relationName, $pivotIds) {
        //
    });

    static::pivotDetached(function ($model, $relationName, $pivotIds) {
        //
    });
    
    static::pivotUpdating(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
        //
    });
    
    static::pivotUpdated(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
        //
    });
    
    static::updating(function ($model) {
        //this is how we catch standard eloquent events
    });
}
```

You can also catch them using dedicated Event Listeners:
```php
\Event::listen('eloquent.*', function ($eventName, array $data) {
    echo $eventName;  //e.g. 'eloquent.pivotAttached'
});
```

## Supported Relationships
**BelongsToMany**  and **MorphToMany**

## Which events are dispatched and when they are dispatched
Four BelongsToMany methods dispatches events from this package :

**attach()**  
Dispatches **one** **pivotAttaching** and **one** **pivotAttached** event.  
Even when more rows are added only **one** event is dispatched for all rows but in that case, you can see all changed row ids in the $pivotIds variable, and the changed row ids with attributes in the $pivotIdsAttributes variable.

**detach()**  
Dispatches **one** **pivotDetaching** and **one** **pivotDetached** event.  
Even when more rows are deleted only **one** event is dispatched for all rows but in that case, you can see all changed row ids in the $pivotIds variable.

**updateExistingPivot()**  
Dispatches **one** **pivotUpdating** and **one** **pivotUpdated** event.   
You can change only one row in the pivot table with updateExistingPivot.

**sync()**  
Dispatches **one** **pivotSyncing** and **one** **pivotSynced** event.  
Whether a row was attached/detached/updated during sync only **one** event is dispatched for all rows but in that case, you can see all the attached/detached/updated rows in the $changes variables.  
E.g. *How does sync work:* The sync first detaches all associations and then attaches or updates new entries one by one.

## Usage

We have three tables in database users(id, name), roles(id, name), role_user(user_id, role_id).
We have two models :

```
class User extends Model
{
    use PivotEventTrait;
    // ...

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    static::pivotSynced(function ($model, $relationName, $changes) {
        echo 'pivotSynced';
        echo get_class($model);
        echo $relationName;
        print_r($changes);
    });    

    static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
        echo 'pivotAttached';
        echo get_class($model);
        echo $relationName;
        print_r($pivotIds);
        print_r($pivotIdsAttributes);
    });
    
    static::pivotUpdated(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
        echo 'pivotUpdated';
        echo get_class($model);
        echo $relationName;
        print_r($pivotIds);
        print_r($pivotIdsAttributes);
    });

    static::pivotDetached(function ($model, $relationName, $pivotIds) {
        echo 'pivotDetached';
        echo get_class($model);
        echo $relationName;
        print_r($pivotIds);
    });

    // ...
}
```

```
class Role extends Model
{
    // ...
}
```

### Attaching

For attach() or detach() one event is dispatched for both pivot ids.

#### Attaching With Primary Key
Running this code
```php
$user = User::first();
$user->roles()->attach(1);
```

You will see this output
```
pivotAttached
App\Models\User
roles
[1]
[1 => []]
```

#### Attaching with array
Running this code
```
$user = User::first();
$user->roles()->attach([1]);
```
You will see this output
```
pivotAttached
App\Models\User
roles
[1]
[1 => []]
```

#### Attaching with model
Running this code
```php
$user = User::first();
$user->roles()->attach(Role::first());
```

You will see this output
```
pivotAttached
App\Models\User
roles
[1]
[1 => []]
```

#### Attaching with collection
Running this code
```php
$user = User::first();
$user->roles()->attach(Role::get());
```

You will see this output
```
pivotAttached
App\Models\User
roles
[1, 2]
[1 => [], 2 => []]
```

#### Attaching with array (id => attributes)
Running this code
```
$user = User::first();
$user->roles()->attach([1, 2 => ['attribute' => 'test']], ['attribute2' => 'test2']);
```
You will see this output
```
pivotAttached
App\Models\User
roles
[1, 2]
[1 => [], 2 => ['attribute' => 'test', 'attribute2' => 'test2']]
```

### Syncing
Running this code
```php
$user = User::first();
$user->roles()->attach([
     1 => ['pivot_attribut' => 1],
     2 => ['pivot_attribut' => 0]
 ]);
 $user->roles()->sync([
     1 => ['pivot_attribut' => 0]
     3 => ['pivot_attribut' => 1]
 ]);
```

You will see this output
```
pivotSynced
App\Models\User
roles
[
   "attached" => [
     0 => 3
   ]
   "detached" => [
     1 => 2
   ]
   "updated" => [
     0 => 1
   ]
 ]
```

### Detaching
Running this code
```
$user = User::first();
$user->roles()->detach([1, 2]);
```
You will see this output
```
pivotAttached
App\Models\User
roles
[1, 2]
```

### Updating

Running this code
```
$user = User::first();
$user->roles()->updateExistingPivot(1, ['attribute' => 'test']);
```
You will see this output
```
pivotUpdated
App\Models\User
roles
[1]
[1 => ['attribute' => 'test']]
```

# Upgrade Guide

## Upgrading to `0.7.0`

### `databaseMigrationsPath` parameter is now an array

`databaseMigrationsPath` parameter is changed to be an `array` from `string`. To allow multiple migration paths.

## Upgrading to 0.6

In previous versions of Larastan, `reportUnmatchedIgnoredErrors` config value was set to `false` by Larastan. Larastan no longer ignores errors on your behalf. Here is how you can fix them yourself:

### Result of function abort \(void\) is used

Stop `return`-ing abort.

```diff
-return abort(401);
+abort(401);
```

### Call to an undefined method Illuminate\\Support\\HigherOrder

Larastan still does not understand this particular magic, you can
[ignore it yourself](docs/errors-to-ignore.md#higher-order-messages) for now.

### Method App\\Exceptions\\Handler::render\(\) should return Illuminate\\Http\\Response but returns Symfony\\Component\\HttpFoundation\\Response

Fix the docblock.

```diff
-    * @return Illuminate\Http\Response|Symfony\Component\HttpFoundation\Response
+    * @return Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
```

### Property App\\Http\\Middleware\\TrustProxies::\$headers \(string\) does not accept default value of type int

Fix the docblock. 

```diff
-    * @var string
+    * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
```

## Upgrading to 0.5.8

### Custom collections
If you are taking advantage of custom Eloquent Collections for your models, you have to mark your custom collection class as generic like so:
```php
/**
 * @template TModel
 * @extends Collection<TModel>
 */
class CustomCollection extends Collection
{
}
```
If your IDE complains about the `template` or `extends` annotation you may also use the PHPStan specific annotations `@phpstan-template` and `@phpstan-extends`

Also in your model file where you are overriding the `newCollection` method, you have to specify the return type like so:

```php
/**
 * @param array<int, YourModel> $models
 *
 * @return CustomCollection<YourModel>
 */
public function newCollection(array $models = []): CustomCollection
{
    return new CustomCollection($models);
}
```

If your IDE complains about the return type annotation you may also use the PHPStan specific return type `@phpstan-return`

## Upgrading to 0.5.6

### Generic Relations
Eloquent relations are now generic classes. Internally, this makes couple of things easier and more flexible. In general it shouldn't affect your code. The only caveat is if you define your custom relations. If you do that, you have to mark your custom relation class as generic like so:

```php
/**
 * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
 * @extends Relation<TRelatedModel>
 */
class CustomRelation extends Relation
{
    //...
}
```

## Upgrading To 0.5.3 From 0.5.2

#### Eloquent Resources
In order to perform proper analysis on your Eloquent resources, you must typehint the underlying Eloquent model class.
This will inform PHPStan that this resource uses `User` model. So calls to `$this` with model property or methods will be inferred correctly.

```bash
/**
 * @extends JsonResource<User>
 */
class UserResource extends JsonResource
{
...
}

```

## Upgrading To 0.5.1 From 0.5.0

#### Eloquent Model property types
0.5.1 introduces ability to infer Eloquent model property types. To take advantage of this you have to remove any model class from `universalObjectCratesClasses` PHPStan configuration parameter, if you added any earlier.

#### Custom Eloquent Builders
If you are taking advantage of custom Eloquent Builders for your models, you have to mark your custom builder class as generic like so:
```php
/**
 * @template TModelClass
 * @extends Builder<TModelClass>
 */
class CustomBuilder extends Builder
{
}
```
If your IDE complains about the `template` or `extends` annotation you may also use the PHPStan specific annotations `@phpstan-template` and `@phpstan-extends`

Also in your model file where you are overriding the `newEloquentBuilder` method, you have to specify the return type like so:

```php
/**
 * @param \Illuminate\Database\Query\Builder $query
 *
 * @return CustomBuilder<YourModelWithCustomBuilder>
 */
public function newEloquentBuilder($query): CustomBuilder
{
    return new CustomBuilder($query);
}
```

If your IDE complains about the return type annotation you may also use the PHPStan specific return type `@phpstan-return`

#### Collection generics
Generic stubs added to Eloquent and Support collections. Larastan is able to take advantage of this and returns the correct collection with its items defined. For example `Collection<User>` represents collection of users. But in case Larastan fails to do so in any case, you can assist with adding a typehint with the appropriate annotation like `@var`, `@param` or `@return` using the syntax `Collection<Model>`

## Upgrading To 0.5 From 0.4

### Updating Dependencies

Update your `nunomaduro/larastan` dependency to `^0.5` in your `composer.json` file.

### `artisan code:analyse`

The artisan `code:analyse` command is no longer available. Therefore, you need to:

1. Start using the phpstan command to launch Larastan.

```bash
./vendor/bin/phpstan analyse
```

If you are getting the error `Allowed memory size exhausted`, then you can use the `--memory-limit` option fix the problem:

```bash
./vendor/bin/phpstan analyse --memory-limit=2G
```

2. Create a `phpstan.neon` or `phpstan.neon.dist` file in the root of your application that might look like this:

```
includes:
    - ./vendor/nunomaduro/larastan/extension.neon

parameters:

    paths:
        - app

    # The level 7 is the highest level
    level: 5

    ignoreErrors:
        - '#Unsafe usage of new static#'

    excludes_analyse:
        - ./*/*/FileToBeExcluded.php

    checkMissingIterableValueType: false
```

### Misc

You may want to be aware of all the BC breaks detailed in:

- PHPStan changelog: [github.com/phpstan/phpstan/releases/tag/0.12.0](https://github.com/phpstan/phpstan/releases/tag/0.12.0)

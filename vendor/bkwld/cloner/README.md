# Cloner

[![Packagist](https://img.shields.io/packagist/v/BKWLD/cloner.svg)](https://packagist.org/packages/bkwld/cloner) [![Build Status](https://travis-ci.org/BKWLD/cloner.svg?branch=master)](https://travis-ci.org/BKWLD/cloner) [![Coverage Status](https://coveralls.io/repos/github/BKWLD/cloner/badge.svg?branch=master)](https://coveralls.io/github/BKWLD/cloner?branch=master)

A trait for Laravel Eloquent models that lets you clone a model and it's relationships, including files. Even to another database.


## Installation

To get started with Cloner, use Composer to add the package to your project's dependencies:

```
composer require bkwld/cloner
```
<br>

> Note: The Below step is optional in Laravel 5.5 or above!

After installing the cloner package, register the service provider.

```php
Bkwld\Cloner\ServiceProvider::class,
```
in your `config/app.php` configuration file:

```php
'providers' => [
    /*
    * Package Service Providers...
    */
    Bkwld\Cloner\ServiceProvider::class,
],
```

## Usage

Your model should now look like this:

```php
class Article extends Eloquent {

   use \Bkwld\Cloner\Cloneable;
}
```

You can clone an Article model like so:

```php
$clone = Article::first()->duplicate();
```

In this example, `$clone` is a new `Article` that has been saved to the database. To clone to a different database:

```php
$clone = Article::first()->duplicateTo('production');
```

Where `production` is the [connection name](https://laravel.com/docs/6.x/database#using-multiple-database-connections) of a different Laravel database connection.


#### Cloning Relationships

Lets say your `Article` has many `Photos` (a one to many relationship) and can have more than one `Authors` (a many to many relationship). Now, your `Article` model should look like this:

```php
class Article extends Eloquent {
   use \Bkwld\Cloner\Cloneable;

   protected $cloneable_relations = ['photos', 'authors'];

   public function photos() {
       return $this->hasMany('Photo');
   }

   public function authors() {
        return $this->belongsToMany('Author');
   }
}
```

The `$cloneable_relations` informs the `Cloneable` as to which relations it should follow when cloning.
Now when you call `Article::first()->duplicate()`, all of the `Photo` rows of the original will be copied and associated with the new `Article`.
And new pivot rows will be created associating the new `Article` with the `Authors` of the original (because it is a many to many relationship, no new `Author` rows are created).
Furthermore, if the `Photo` model has many of some other model, you can specify `$cloneable_relations` in its class and `Cloner` will continue replicating them as well.

> **Note:** Many to many relationships will not be cloned to a _different_ database because the related instance may not exist in the other database or could have a different primary key.

### Customizing the cloned attributes

By default, `Cloner` does not copy the `id` (or whatever you've defined as the `key` for the model) field; it assumes a new value will be auto-incremented.
It also does not copy the `created_at` or `updated_at`.
You can add additional attributes to ignore as follows:

```php
class Photo extends Eloquent {
   use \Bkwld\Cloner\Cloneable;

   protected $clone_exempt_attributes = ['uid', 'source'];

   public function article() {
        return $this->belongsTo('Article');
   }

   public function onCloning($src, $child = null) {
        $this->uid = str_random();
        if($child) echo 'This was cloned as a relation!';
        echo 'The original key is: '.$src->getKey();
   }
}
```

The `$clone_exempt_attributes` adds to the defaults.
If you want to replace the defaults altogether, override the trait's `getCloneExemptAttributes()` method and return an array.

Also, note the `onCloning()` method in the example.
It is being used to make sure a unique column stays unique.
The `Cloneable` trait adds to no-op callbacks that get called immediately before a model is saved during a duplication and immediately after: `onCloning()` and `onCloned()`.
The `$child` parameter allows you to customize the behavior based on if it's being cloned as a relation or direct.

In addition, Cloner fires the following Laravel events during cloning:

- `cloner::cloning: ModelClass`
- `cloner::cloned: ModelClass`

`ModelClass` is the classpath of the model being cloned.
The event payload contains the clone and the original model instances.


### Cloning files

If your model references files saved disk, you'll probably want to duplicate those files and update the references.
Otherwise, if the clone is deleted and it cascades delets, you will delete files referenced by your original model.  `Cloner` allows you to specify a file attachment adapter and ships with support for [Bkwld\Upchuck](https://github.com/BKWLD/upchuck).
Here's some example usage:

```php
class Photo extends Eloquent {
   use \Bkwld\Cloner\Cloneable;

   protected $cloneable_file_attributes = ['image'];

   public function article() {
        return $this->belongsTo('Article');
   }
}
```

The `$cloneable_file_attributes` property is used by the `Cloneable` trait to identify which columns contain files.  Their values are passed to the attachment adapter, which is responsible for duplicating the files and returning the path to the new file.

If you don't use [Bkwld\Upchuck](https://github.com/BKWLD/upchuck) you can write your own implementation of the `Bkwld\Cloner\AttachmentAdapter` trait and wrap it in a Laravel IoC container named 'cloner.attachment-adapter'.
For instance, put this in your `app/start/global.php`:

```php
App::singleton('cloner.attachment-adapter', function($app) {
   return new CustomAttachmentAdapter;
});
```

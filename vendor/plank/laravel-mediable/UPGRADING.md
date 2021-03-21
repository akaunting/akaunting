# Upgrading

## 3.x to 4.x

* UrlGenerators no longer throw `MediaUrlException` when the file does not have public visibility. This removes the need to read IO for files local disks or to make HTTP calls for files on s3 disks. Visibility can still checked with `$media->isPubliclyAccessible()`, if necessary.
* Highly recommended to explicitly specify the `'url'` config value on all disks used to generate URLs.
* No longer reading the `'prefix'` config of local disks. Value should be included in the `'url'` config instead.  

## 2.x to 3.x

* Minimum PHP version moved to 7.2
* Minimum Laravel version moved to 5.6
* All methods now have parameter and return type hints. If extending any class or implementing any interface from this package, method signatures will need to be updated. 

## 1.x to 2.x

You need to add an order column to the mediables table.

```php
$table->integer('order')->unsigned()->index();
```

A handful of methods have been renamed on the `MediaUploader` class.

`setFilename` -> `useFilename`
`setDisk` -> `toDisk`
`setDirectory` -> `toDirectory`


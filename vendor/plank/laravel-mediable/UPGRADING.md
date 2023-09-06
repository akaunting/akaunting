# Upgrading

## 4.x to 5.x

* Database migration files are now served from within the package. In your migrations table, rename the `XXXX_XX_XX_XXXXXX_create_mediable_tables.php` entry to `2016_06_27_000000_create_mediable_tables.php` and delete your local copy of the migration file from the /database/migrations directory. If any customizations were made to the tables, those should be defined as one or more separate ALTER table migrations.
* Two columns added to the `media` table: `variant_name` (varchar)  and `original_media_id` (should match `media.id` column type). Migration file is included with the package.
* `Plank\Mediable\MediaUploaderFacade` moved to `Plank\Mediable\Facades\MediaUploader`
* Directory and filename validation now only allows URL and filesystem safe ASCII characters (alphanumeric plus `.`, `-`, `_`, and `/` for directories). Will automatically attempt to transliterate UTF-8 accented characters and ligatures into their ASCII equivalent, all other characters will be converted to hyphens.
* The following methods now include an extra `$withVariants` parameter :
    * `Mediable::scopeWithMedia()`
    * `Mediable::scopeWithMediaMatchAll()`
    * `Mediable::loadMedia()`
    * `Mediable::loadMediaMatchAll()`
    * `MediableCollection::loadMedia()`
    * `MediableCollection::loadMediaMatchAll()`

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


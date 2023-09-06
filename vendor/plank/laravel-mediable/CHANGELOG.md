# Changelog

## 5.5.0 - 2022-05-09
- Filename and pathname sanitization will use the app locale when transliterating UTF-8 characters to ascii.
- Restored original behaviour of treating unrecognized mime types as `application/octet-stream` (changed in recent version of Flysystem)

## 5.4.1 - 2022-04-06
- Fixed S3 temporary URL generation for Laravel 9+ / Flysystem 3+

## 5.4.0 - 2022-02-11
- Added support for Laravel 9
- Dropped support for PHP 7.3
- Dropped support for Laravel 6.x and 7.x
- Fixed S3 temporary URL generation not respecting disk root configuration.

## 5.3.1 - 2021-12-11
- Support specifying file visibility on variant creation

## 5.2.1 - 2021-10-27
- Fix aggregate type matching not properly handling case mismatches of mime types and/or extensions
- Handle Guzzle stream_for()` deprecation

## 5.2.0 - 2021-09-08
- Allow passing filesystem options via uploader and mover

## 5.1.1 - 2021-04-29
- Fixed Facade PHPDOC typehints

## 5.1.0 - 2021-04-28
- Added `MediaUploader::onDuplicateReplaceWithVariants()` which behaves similar to `onDuplicateReplace()` but will also delete any variants of the replaced Media record.
- Fixed `onDuplicateUpdate()` failing if file exists but without a matching model.

## 5.0.7 - 2020-12-11
- Fixed `MediaUploader` Facade returning the same instance

## 5.0.6 - 2020-12-05
- Resolve bugs with PHP 8.0

## 5.0.5 - 2020-12-05
- `ImageManipulator` now uses `$media->contents()` instead of `$media->stream()`, as Intervention Image loads the whole file into memory anyways, and the former seems to have fewer hiccups for various cloud filesystems.

## 5.0.4 - 2020-12-02
- Fixed serialization of `CreateImageVariants` job.

## 5.0.3 - 2020-10-28
- Fixed docblock of `attachMedia()` and `SyncMedia()` (Thanks @hailwood!)

## 5.0.2 - 2020-10-22
- Fixed additional bugs with the `MediableCollection::delete()` method
- Optimized the execution speed of `MediableCollection::delete()`

## 5.0.1 - 2020-10-22
- fixed notices generated from collection offset access in `MediableCollection::delete()`

## 5.0.0 - 2020-10-14
- Added support for creating image variants using the intervention/image library. Variants can be created synchronously in the current process or asychronously as queued jobs. Media records keep track of variants created from them.
- Fixed Laravel 8+ migration squashing. Database migrations are now loaded from within the package instead of copied to the application's database/migration directory. See UPGRADING.md for steps to avoid conflicts.
- Directory and filename validation now only allows URL and filesystem safe ASCII characters (alphanumeric plus `.`, `-`, `_`, and `/` for directories). Will automatically attempt to transliterate UTF-8 accented characters and ligatures into their ASCII equivalent, all other characters will be converted to hyphens.
- Added `Media::stream()` method to easily retrieve a PSR-7 compatible Stream.
- Added support for generating temporary URLs for files hosted on Amazon S3 buckets.

## 4.4.2 - 2020-09-26
- Fixed a handful of bugs related to using a custom table name when using a custom media class

## 4.4.1 - 2020-09-14
- Fixed Morph relation when subclassing Media (Thanks @GeoSot!)

## 4.4.0 - 2020-09-09
- Added support for Laravel 8.0
- Dropping support for Laravel versions < 6.0
- Dropping support for PHP versions 7.2

## 4.3.2 - 2020-08-15
- Fix composer version constraint of `league/flysystem` to allow minor version bumps
- Removed redundant index from the Media table database migration

## 4.3.1 - 2020-07-29
- `Media::moveToDisk()` and `Media::copyToDisk()` now correctly transfer file visibility to the new disk.

## 4.3.0 - 2020-07-27
- Added `Media::moveToDisk()` and `Media::copyToDisk()` methods.

## 4.2.3 - 2020-06-02
- The `Media::$size` property is now cast as int, fixing a TypeError. (Thanks @boumanb!)
- Fixed `RemoteUrlAdapter`, `StreamAdapter`, and `StreamResourceAdapter` potentially returning an incorrect filename and/or extension if the query params of the URL contains certain characters.

## 4.2.2 - 2020-05-13
- Fix bug with package auto-discovery with PHP 7.4
- Fix issue caused by a bug with doctrine/inflector 1.4.0

## 4.2.1 - 2020-03-10
- Replaced usage of the `getClientSize()` method deprecated in Symphony 4.1 with `getSize()`

## 4.2.0 - 2020-03-06
- Added support for Laravel 7.0

## 4.1.0 - 2020-02-29
- Fixed the timing of the beforeSave callback. Now occurs before onDuplicate validation occurs. This allows the callback to be used to determine where to place the file
- The beforeSave callback is now called triggered by the `MediaUploader::replace()` and `MediaUploader::import()` methods as well

## 4.0.1 - 2020-02-18
- Added support for the new Symfony MimeTypes class in favor of the deprecated ExtensionGuesser (Thanks @crishoj!)

## 4.0.0 - 2019-10-11
- changed UrlGenerators to use the underlying filesystemAdapter's `url()` method
- UrlGenerators no longer throw `MediaUrlException` when the file does not have public visibility. This removes the need to read IO for files local disks or to make HTTP calls for files on s3 disks.
- Removed `LocalUrlGenerator::getPublicPath()`
- No longer reading the `'prefix'` config of local disks. Value should be included in the `'url'` config instead.

## 3.0.1 - 2019-09-18
- Fixed public visibility not being respected when generating URLs for local files that are not in the webroot.

## 3.0.0 - 2019-09-16
- Updated minimum support requirements to PHP 7.2 and Laravel 5.6+.
- Added PHP 7 parameter and return type hints across the board
- Added a new method `getStreamResource` to `SourceAdapterInterface`, uploader will now attempt to use a stream to reduce memory usage.
- Added `delete()` method to `MediableCollection` for mass deleting media records and files.
- Added support for file visibility on a file-by-file basis.
- Cleaned up test suite.
- fixed a number of docblocks

## 2.9.0 - 2019-03-22
- The name of the Mediables pivot table is now configurable (Thanks @nadinengland!)

## 2.8.2 - 2019-03-08
- Fix windows paths pattern (Thanks @aalyusuf!)

## 2.8.1 - 2019-01-27
- Add methods to facade for IDE autocompletion (Thanks @simonschaufi!)

## 2.8.0 - 2018-09-20
- Added update on duplicate behaviour to MediaUploader (Thanks @pet1330!)
- Fixed remote URL data source raising an error when headers cannot be read (Thanks @sebdesign!)

## 2.7.3 - 2018-07-05
- Return correct types in source adapter methods (Thanks @sebdesign!)
- Add docblocks for Media properties and query scopes (Thanks @sebdesign!)

## 2.7.2 - 2018-07-03
- Fixed docblocks (Thanks @sebdesign!)

## 2.7.1 - 2018-06-04
- Fixed tags with numeric values

## 2.7.0 - 2018-05-11
- Added `MediaUploader::verifyFile()` to apply validation to a source without uploading (Thanks @JulesPrimo)
- Added `MediaUploader::beforeSave()` to allow editing custom fields on Media records before they are saved (Thanks @JulesPrimo)

## 2.6.2 - 2018-03-11
- Fix URL generation for local disks using symbolic links in different Laravel versions (Thanks @sebdesign !)

## 2.6.1 - 2018-02-20
- `MediaUploader::onDuplicateIncrement()` behaviour adjusted to use hyphens instead of parenthesis (Thanks @ryankilf!)

## 2.6.0 - 2018-02-13
- Added `Media::copyTo()` method (Thanks @johannesschobel!)

## 2.5.0 - 2017-08-30
- Added `Mediable::lastMedia()` convenience method (Thanks @pet1330!)

## 2.4.8 - 2017-08-18
- Added Laravel 5.5 package autodiscovery
- Fixed bugs due to method renamed in Laravel 5.5

## 2.4.7 - 2017-05-04
- Added missing use statements.

## 2.4.6 - 2017-05-04
- Fixed composer notation use.

## 2.4.5 - 2017-05-04
- Added fallback extension guesser to various SourceAdapters for cases where file path does not include extension (e.g. tmp files).

## 2.4.4 - 2017-03-08
- Fixed allowed extension checking failing due to case mismatch

## 2.4.3 - 2017-02-15
- Restored Laravel 5.2 compatibility
- `S3UrlGenerator` now generates the url directly with S3 client, instead of with the `FilesystemAdapter::url()` method, which was only added in Laravel 5.2.15
- Added fallback for `wherePivotIn()` used in eager loading, which was only added in Laravel 5.3

## 2.4.2 - 2017-02-12
- Fixed issues cause by Laravel 5.4 backwards-compatibility breaks
- Increased laravel minumum version to 5.3, which is the minimum that works with the current implementation. Will attempt to restore support for older versions in an upcoming release.


## 2.4.1 - 2016-12-30
- The `onDuplicateDelete` action of the `MediaUploader` now manually deletes the `Media` record and the file on disk, instead of depending on the record existing to clean its own file.

## 2.4.0 - 2016-12-10
- Added support for raw content strings to `MediaUploader` (Thanks @sebdesign)
- Added support for stream resources to `MediaUploader` (Thanks @sebdesign)
- Added support for PSR-7 StreamInterface objects to `MediaUploader` (Thanks @sebdesign)
- All SourceAdapters now properly adhere to the described interface.
- Refactored test suite for speed.

## 2.3.0 - 2016-11-17
- Separated MediaUploadException into a number of subclasses for more granular exception handling (Thanks @sebdesign!).
- Added HandlesMediaUploadExceptions trait for converting MediaUploadExceptions into HttpException with appropriate error codes (Thanks @sebdesign).

## 2.2.3 - 2016-11-13
- Fixed SQL escaping issue in `Mediable::getOrderValueForTags`.

## 2.2.2 - 2016-10-07
- Fixed `Media::scopeForPathOnDisk` not working when path does not contain a directory (Thanks @geidelguerra!).

## 2.2.1 - 2016-10-05
- Fixed typo in `MediaUploader`'s `OnDuplicateError` behaviour (Thanks @geidelguerra!).

## 2.2.0 - 2016-09-30
- Added handling for symlinked local disks.
- fixed minor issue where variable could be undefined.

## 2.1.0 - 2016-09-24
- Added means of removing order by from media relation query.
- Fixed multiple media passed to `attachMedia()` or `syncMedia()` receiving the same order value.
- Fixed issue with ONLY_FULL_GROUP_BY (MySQL 5.6.5+).
- Reworked `attachMedia()` to optimize the number of executed queries.


## 2.0.0 - 2016-09-17
- `Mediable` models now remember the order in which `Media` is attached to each tag.
- Renamed a few `MediaUploader` methods.
- Facilitated setting `MediaUploader` on-duplicate behaviour. Thanks @jdhmtl.
- `MediaUploader` can now generate filenames using hash of file contents (Thanks @geidelguerra!).
- Added `import()` and `update()` methods to `MediaUploader`.

## 1.1.1 - 2016-08-16
- Published migration file now uses dynamic timestamp (Thanks @borisdamevin!).

## 1.1.0 - 2016-08-14
- Added behaviour for detaching mediable relationships when Media or Mediable models are deleted or soft deleted.

## 1.0.1 - 2016-08-12
- Fixed `Mediable` relationship not connecting to custom `Media` subclass defined in config.

## 1.0.0 - 2016-08-04
- Added match-all case to media eager load helpers.
- `Mediable::getTagsForMedia()` now properly rehydrates media if necessary.
- `Mediable::load()` now looks for media that is either the $relationship key or value.

## 0.3.0 - 2016-07-25
- Added MediaCollection class.
- Added media eager loading helpers to query builder, `Mediable`, and MediaCollection.

## 0.2.0 - 2016-07-21
- Added object typehints to all appropriate functions and closures.

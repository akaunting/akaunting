# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.8.0

- Deprecate HttplugFactory, use Psr17Factory instead
- Make depencendy on php-http/message-factory optional

## 1.7.0

- Bump to PHP 7.2 minimum
- Allow psr/http-message v2
- Use copy-on-write for streams created from strings

## 1.6.1

- Security fix: CVE-2023-29197

## 1.6.0

### Changed

- Seek to the begining of the string when using Stream::create()
- Populate ServerRequest::getQueryParams() on instantiation
- Encode [reserved characters](https://www.rfc-editor.org/rfc/rfc3986#appendix-A) in userinfo in Uri
- Normalize leading slashes for Uri::getPath()
- Make Stream's constructor public
- Add some missing type checks on arguments

## 1.5.1

### Fixed

- Fixed deprecations on PHP 8.1

## 1.5.0

### Added

- Add explicit `@return mixed`
- Add explicit return types to HttplugFactory

### Fixed

- Improve error handling with streams

## 1.4.1

### Fixed

- `Psr17Factory::createStreamFromFile`, `UploadedFile::moveTo`, and
  `UploadedFile::getStream` no longer throw `ValueError` in PHP 8.

## 1.4.0

### Removed

The `final` keyword was replaced by `@final` annotation.

## 1.3.2

### Fixed

- `Stream::read()` must not return boolean.
- Improved exception message when using wrong HTTP status code.

## 1.3.1

### Fixed

- Allow installation on PHP8

## 1.3.0

### Added

- Make Stream::__toString() compatible with throwing exceptions on PHP 7.4.

### Fixed

- Support for UTF-8 hostnames
- Support for numeric header names

## 1.2.1

### Changed

- Added `.github` and `phpstan.neon.dist` to `.gitattributes`.

## 1.2.0

### Changed

- Change minimal port number to 0 (unix socket)
- Updated `Psr17Factory::createResponse` to respect the specification. If second
  argument is not used, a standard reason phrase. If an empty string is passed,
  then the reason phrase will be empty.

### Fixed

- Check for seekable on the stream resource.
- Fixed the `Response::$reason` should never be null.

## 1.1.0

### Added

- Improved performance
- More tests for `UploadedFile` and `HttplugFactory`

### Removed

- Dead code

## 1.0.1

### Fixed

- Handle `fopen` failing in createStreamFromFile according to PSR-7.
- Reduce execution path to speed up performance.
- Fixed typos.
- Code style.

## 1.0.0

### Added

- Support for final PSR-17 (HTTP factories). (`Psr17Factory`)
- Support for numeric header values.
- Support for empty header values.
- All classes are final
- `HttplugFactory` that implements factory interfaces from HTTPlug.

### Changed

- `ServerRequest` does not extend `Request`.

### Removed

- The HTTPlug discovery strategy was removed since it is included in php-http/discovery 1.4.
- `UploadedFileFactory()` was removed in favor for `Psr17Factory`.
- `ServerRequestFactory()` was removed in favor for `Psr17Factory`.
- `StreamFactory`, `UriFactory`, abd `MessageFactory`. Use `HttplugFactory` instead.
- `ServerRequestFactory::createServerRequestFromArray`, `ServerRequestFactory::createServerRequestFromArrays` and
  `ServerRequestFactory::createServerRequestFromGlobals`. Please use the new `nyholm/psr7-server` instead.

## 0.3.0

### Added

- Return types.
- Many `InvalidArgumentException`s are thrown when you use invalid arguments.
- Integration tests for `UploadedFile` and `ServerRequest`.

### Changed

- We dropped PHP7.0 support.
- PSR-17 factories have been marked as internal. They do not fall under our BC promise until PSR-17 is accepted.
- `UploadedFileFactory::createUploadedFile` does not accept a string file path.

## 0.2.3

No changelog before this release

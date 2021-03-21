### 1.8.1 (2020-08-13)

  * Added type annotations

### 1.8.0 (2020-04-30)

  * Improved lexer performance
  * Added (tentative) support for PHP 8
  * Fixed wording of error reporting for invalid strings when the error happened after the 20th character

### 1.7.2 (2019-10-24)

  * Fixed issue decoding some unicode escaped characters (for " and ')

### 1.7.1 (2018-01-24)

  * Fixed PHP 5.3 compatibility in bin/jsonlint

### 1.7.0 (2018-01-03)

  * Added ability to lint multiple files at once using the jsonlint binary

### 1.6.2 (2017-11-30)

  * No meaningful public changes

### 1.6.1 (2017-06-18)

  * Fixed parsing of `0` as invalid

### 1.6.0 (2017-03-06)

  * Added $flags arg to JsonParser::lint() to take the same flag as parse() did
  * Fixed backtracking performance issues on long strings with a lot of escaped characters

### 1.5.0 (2016-11-14)

  * Added support for PHP 7.1 (which converts `{"":""}` to an object property called `""` and not `"_empty_"` like 7.0 and below).

### 1.4.0 (2015-11-21)

  * Added a DuplicateKeyException allowing for more specific error detection and handling

### 1.3.1 (2015-01-04)

  * Fixed segfault when parsing large JSON strings

### 1.3.0 (2014-09-05)

  * Added parsing to an associative array via JsonParser::PARSE_TO_ASSOC
  * Fixed a warning when rendering parse errors on empty lines

### 1.2.0 (2014-07-20)

  * Added support for linting multiple files at once in bin/jsonlint
  * Added a -q/--quiet flag to suppress the output
  * Fixed error output being on STDOUT instead of STDERR
  * Fixed parameter parsing

### 1.1.2 (2013-11-04)

  * Fixed handling of Unicode BOMs to give a better failure hint

### 1.1.1 (2013-02-12)

  * Fixed handling of empty keys in objects in certain cases

### 1.1.0 (2012-12-13)

  * Added optional parsing of duplicate keys into key.2, key.3, etc via JsonParser::ALLOW_DUPLICATE_KEYS
  * Improved error reporting for common mistakes

### 1.0.1 (2012-04-03)

  * Added optional detection and error reporting for duplicate keys via JsonParser::DETECT_KEY_CONFLICTS
  * Added ability to pipe content through stdin into bin/jsonlint

### 1.0.0 (2012-03-12)

  * Initial release

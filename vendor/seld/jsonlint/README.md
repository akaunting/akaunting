JSON Lint
=========

[![Build Status](https://secure.travis-ci.org/Seldaek/jsonlint.png)](http://travis-ci.org/Seldaek/jsonlint)

Usage
-----

```php
use Seld\JsonLint\JsonParser;

$parser = new JsonParser();

// returns null if it's valid json, or a ParsingException object.
$parser->lint($json);

// Call getMessage() on the exception object to get
// a well formatted error message error like this

// Parse error on line 2:
// ... "key": "value"    "numbers": [1, 2, 3]
// ----------------------^
// Expected one of: 'EOF', '}', ':', ',', ']'

// Call getDetails() on the exception to get more info.

// returns parsed json, like json_decode() does, but slower, throws
// exceptions on failure.
$parser->parse($json);
```

You can also pass additional flags to `JsonParser::lint/parse` that tweak the functionality:

- `JsonParser::DETECT_KEY_CONFLICTS` throws an exception on duplicate keys.
- `JsonParser::ALLOW_DUPLICATE_KEYS` collects duplicate keys. e.g. if you have two `foo` keys they will end up as `foo` and `foo.2`.
- `JsonParser::PARSE_TO_ASSOC` parses to associative arrays instead of stdClass objects.

Example:

```php
$parser = new JsonParser;
try {
    $parser->parse(file_get_contents($jsonFile), JsonParser::DETECT_KEY_CONFLICTS);
} catch (DuplicateKeyException $e) {
    $details = $e->getDetails();
    echo 'Key '.$details['key'].' is a duplicate in '.$jsonFile.' at line '.$details['line'];
}
```

> **Note:** This library is meant to parse JSON while providing good error messages on failure. There is no way it can be as fast as php native `json_decode()`.
>
> It is recommended to parse with `json_decode`, and when it fails parse again with seld/jsonlint to get a proper error message back to the user. See for example [how Composer uses this library](https://github.com/composer/composer/blob/56edd53046fd697d32b2fd2fbaf45af5d7951671/src/Composer/Json/JsonFile.php#L283-L318):


Installation
------------

For a quick install with Composer use:

    $ composer require seld/jsonlint

JSON Lint can easily be used within another app if you have a
[PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
autoloader, or it can be installed through [Composer](https://getcomposer.org/)
for use as a CLI util.
Once installed via Composer you can run the following command to lint a json file or URL:

    $ bin/jsonlint file.json

Requirements
------------

- PHP 5.3+
- [optional] PHPUnit 3.5+ to execute the test suite (phpunit --version)

Submitting bugs and feature requests
------------------------------------

Bugs and feature request are tracked on [GitHub](https://github.com/Seldaek/jsonlint/issues)

Author
------

Jordi Boggiano - <j.boggiano@seld.be> - <http://twitter.com/seldaek>

License
-------

JSON Lint is licensed under the MIT License - see the LICENSE file for details

Acknowledgements
----------------

This library is a port of the JavaScript [jsonlint](https://github.com/zaach/jsonlint) library.

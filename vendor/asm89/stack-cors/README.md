# Stack/Cors

Library and middleware enabling cross-origin resource sharing for your
http-{foundation,kernel} using application. It attempts to implement the
[W3C Recommendation] for cross-origin resource sharing.

[W3C Recommendation]: http://www.w3.org/TR/cors/

Build status: ![.github/workflows/run-tests.yml](https://github.com/asm89/stack-cors/workflows/.github/workflows/run-tests.yml/badge.svg)

## Installation

Require `asm89/stack-cors` using composer.

## Usage

This package can be used as a library or as [stack middleware].

[stack middleware]: http://stackphp.com/

### Options

| Option                 | Description                                                | Default value |
|------------------------|------------------------------------------------------------|---------------|
| allowedMethods         | Matches the request method.                                | `array()`     |
| allowedOrigins         | Matches the request origin.                                | `array()`     |
| allowedOriginsPatterns | Matches the request origin with `preg_match`.              | `array()`  |
| allowedHeaders         | Sets the Access-Control-Allow-Headers response header.     | `array()`     |
| exposedHeaders         | Sets the Access-Control-Expose-Headers response header.    | `false`       |
| maxAge                 | Sets the Access-Control-Max-Age response header.           | `false`       |
| supportsCredentials    | Sets the Access-Control-Allow-Credentials header.          | `false`       |

The _allowedMethods_ and _allowedHeaders_ options are case-insensitive.

You don't need to provide both _allowedOrigins_ and _allowedOriginsPatterns_. If one of the strings passed matches, it is considered a valid origin.

If `array('*')` is provided to _allowedMethods_, _allowedOrigins_ or _allowedHeaders_ all methods / origins / headers are allowed.

### Example: using the library

```php
<?php

use Asm89\Stack\CorsService;

$cors = new CorsService(array(
    'allowedHeaders'         => array('x-allowed-header', 'x-other-allowed-header'),
    'allowedMethods'         => array('DELETE', 'GET', 'POST', 'PUT'),
    'allowedOrigins'         => array('http://localhost'),
    'allowedOriginsPatterns' => array('/localhost:\d/'),
    'exposedHeaders'         => false,
    'maxAge'                 => false,
    'supportsCredentials'    => false,
));

$cors->addActualRequestHeaders(Response $response, $origin);
$cors->handlePreflightRequest(Request $request);
$cors->isActualRequestAllowed(Request $request);
$cors->isCorsRequest(Request $request);
$cors->isPreflightRequest(Request $request);
```

## Example: using the stack middleware

```php
<?php

use Asm89\Stack\Cors;

$app = new Cors($app, array(
    // you can use array('*') to allow any headers
    'allowedHeaders'      => array('x-allowed-header', 'x-other-allowed-header'),
    // you can use array('*') to allow any methods
    'allowedMethods'      => array('DELETE', 'GET', 'POST', 'PUT'),
    // you can use array('*') to allow requests from any origin
    'allowedOrigins'      => array('localhost'),
    // you can enter regexes that are matched to the origin request header
    'allowedOriginsPatterns' => array('/localhost:\d/'),
    'exposedHeaders'      => false,
    'maxAge'              => false,
    'supportsCredentials' => false,
));
```

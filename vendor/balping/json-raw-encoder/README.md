# Json Raw Encoder

Use this package to encode arrays to JSON with raw JS objects (eg. callbacks) in them.


## Installation

```bash
composer require balping/json-raw-encoder
```



## Usage

Suppose you need to pass a callback to a JSON object.

```php
<?php
    $array = [
        'type' => 'cat',
        'count' => 42,
        'callback' => 'function(a){alert(a);}'
    ];
?>

<script>
    let bar = <?php echo json_encode($array); ?>;
    bar.callback('hello'); //error
</script>
```

However, the above array will be encoded as

```json
{"type":"cat","count":42,"callback":"function(a){alert(a);}"}
```

On this object, you cannot call `callback()`, as `callback` is a string and not a function.

To get around this problem, use `Raw` objects provided by this package:

```php
<?php
    use Balping\JsonRaw\Raw;
    use Balping\JsonRaw\Encoder;

    $array = [
        'type' => 'cat',
        'count' => 42,
        'callback' => new Raw('function(a){alert(a);}')
    ];
?>

<script>
    let bar = <?php echo Encoder::encode($array); ?>;
    bar.callback('hello'); //prints hello
</script>
```

Now, the encoded JSON looks like this. Notice, that there are no quotation marks around the function.

```js
{"type":"cat","count":42,"callback":function(a){alert(a);}}
```

Calling `bar.callback()` now works, as `callback` is a function and not a string.

## Using with third party libraries

It is possible that the serialisation is done by a library (eg. Fractal), and not by your code, i.e. you cannot replace `json_encode` with `Encoder::encode()`.

In this case, you can still pass callbacks to JSON, by passing the encoded json and an array of all raw objects to `Replacer::replace()`:

```php

use Balping\JsonRaw\Raw;
use Balping\JsonRaw\Replacer;

$rawObjects = [];

$array = [
    'type' => 'cat',
    'count' => 42,
    'callback' => $rawObjects[] = new Raw('function(a){alert(a);}')
];

// you cannot alter the behaviour of a third party encoder
$encoded = $thirdParty->jsonEncode($array);

echo Replacer::replace($encoded, $rawObjects);
```

Result:

```js
{"type":"cat","count":42,"callback":function(a){alert(a);}}
```

## License

This package is licensed under GPLv3.

## Download statistics

[![statistics](https://packagist-statistics.dura.hu/balping/json-raw-encoder/10days.svg)](https://packagist-statistics.dura.hu/balping/json-raw-encoder/10days.svg)

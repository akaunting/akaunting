# A better PHP backtrace

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/backtrace.svg?style=flat-square)](https://packagist.org/packages/spatie/backtrace)
![Tests](https://github.com/spatie/backtrace/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/backtrace.svg?style=flat-square)](https://packagist.org/packages/spatie/backtrace)

To get the backtrace in PHP you can use the `debug_backtrace` function. By default, it can be hard to work with. The
reported function name for a frame is skewed: it belongs to the previous frame. Also, options need to be passed using a bitmask.

This package provides a better way than `debug_backtrace` to work with a back trace. Here's an example:

```php
// returns an array with `Spatie\Backtrace\Frame` instances
$frames = Spatie\Backtrace\Backtrace::create()->frames(); 

$firstFrame = $frames[0];

$firstFrame->file; // returns the file name
$firstFrame->lineNumber; // returns the line number
$firstFrame->class; // returns the class name
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/backtrace.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/backtrace)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can
support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.
You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards
on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/backtrace
```

## Usage

This is how you can create a backtrace instance:

```php
$backtrace = Spatie\Backtrace\Backtrace::create();
```

### Getting the frames

To get all the frames you can call `frames`.

```php
$frames = $backtrace->frames(); // contains an array with `Spatie\Backtrace\Frame` instances
```

A `Spatie\Backtrace\Frame` has these properties:

- `file`: the name of the file
- `lineNumber`: the line number
- `arguments`: the arguments used for this frame. Will be `null` if `withArguments` was not used.
- `class`: the class name for this frame. Will be `null` if the frame concerns a function.
- `method`: the method used in this frame
- `applicationFrame`: contains `true` is this frame belongs to your application, and `false` if it belongs to a file in
  the vendor directory

### Collecting arguments

For performance reasons, the frames of the back trace will not contain the arguments of the called functions. If you
want to add those use the `withArguments` method.

```php
$backtrace = Spatie\Backtrace\Backtrace::create()->withArguments();
```

#### Reducing arguments

For viewing purposes, arguments can be reduced to a string:

```php
$backtrace = Spatie\Backtrace\Backtrace::create()->withArguments()->reduceArguments();
```

By default, some typical types will be reduced to a string. You can define your own reduction algorithm per type by implementing an `ArgumentReducer`:

```php
class DateTimeWithOtherFormatArgumentReducer implements ArgumentReducer
{
    public function execute($argument): ReducedArgumentContract
    {
        if (! $argument instanceof DateTimeInterface) {
            return UnReducedArgument::create();
        }

        return new ReducedArgument(
            $argument->format('d/m/y H:i'),
            get_class($argument),
        );
    }
}
```

This is a copy of the built-in argument reducer for `DateTimeInterface` where we've updated the format. An `UnReducedArgument` object is returned when the argument is not of the expected type. A `ReducedArgument` object is returned with the reduced value of the argument and the original type of the argument.

The reducer can be used as such:

```php
$backtrace = Spatie\Backtrace\Backtrace::create()->withArguments()->reduceArguments(
    Spatie\Backtrace\Arguments\ArgumentReducers::default([
        new DateTimeWithOtherFormatArgumentReducer()
    ])
);
```

Which will first execute the new reducer and then the default ones.

### Setting the application path

You can use the `applicationPath` to pass the base path of your app. This value will be used to determine whether a
frame is an application frame, or a vendor frame. Here's an example using a Laravel specific function.

```php
$backtrace = Spatie\Backtrace\Backtrace::create()->applicationPath(base_path());
```

### Getting a certain part of a trace

If you only want to have the frames starting from a particular frame in the backtrace you can use
the `startingFromFrame` method:

```php
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;

$frames = Backtrace::create()
    ->startingFromFrame(function (Frame $frame) {
        return $frame->class === MyClass::class;
    })
    ->frames();
```

With this code, all frames before the frame that concerns `MyClass` will have been filtered out.

Alternatively, you can use the `offset` method, which will skip the given number of frames. In this example the first 2 frames will not end up in `$frames`.

```php
$frames = Spatie\Backtrace\Backtrace::create()
    ->offset(2)
    ->frames();
```

### Limiting the number of frames

To only get a specific number of frames use the `limit` function. In this example, we'll only get the first two frames.

```php
$frames = Spatie\Backtrace\Backtrace::create()
    ->limit(2)
    ->frames();
```

###  Getting a backtrace for a throwable

Here's how you can get a backtrace for a throwable.

```php
$frames = Spatie\Backtrace\Backtrace::createForThrowable($throwable)
```

Because we will use the backtrace that is already available the throwable, the frames will always contain the arguments used.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van de Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

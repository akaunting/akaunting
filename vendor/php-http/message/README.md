# HTTP Message

[![Latest Version](https://img.shields.io/github/release/php-http/message.svg?style=flat-square)](https://github.com/php-http/message/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![tests](https://github.com/php-http/message/actions/workflows/ci.yml/badge.svg)](https://github.com/php-http/message/actions/workflows/ci.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/php-http/message.svg?style=flat-square)](https://packagist.org/packages/php-http/message)

**HTTP Message related tools.**


## Install

Via Composer

``` bash
$ composer require php-http/message
```


## Intro

This package contains various PSR-7 tools which might be useful in an HTTP workflow:

- Authentication method implementations
- Various Stream encoding tools
- Message decorators
- Message factory implementations for Guzzle PSR-7 and Diactoros
- Cookie implementation
- Request matchers


## Documentation

Please see the [official documentation](http://docs.php-http.org/en/latest/message.html).


## Testing

``` bash
$ composer test
```


## Credits

Thanks to [Cuzzle](https://github.com/namshi/cuzzle) for inpiration for the `CurlCommandFormatter`.


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

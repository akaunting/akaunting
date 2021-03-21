# Contributing

Contributions to this project are always welcome. If you notice a bug or have an idea for a feature, please feel to send a pull request via [Github](https://github.com/plank/laravel-mediable).

Please make sure to adhere to the following guidelines:

- Please adhere to the [PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
- Write unit tests for any functionality you are adding
- Any new features or changes in behaviour should be explained in the documentation. Don't forget to build the docs.
- Send one pull request per feature and send each from their own feature branch. Don't send a pull request from your master branch.


## Tests

The test suite can be run using phpunit

```bash
$ phpunit
```

## Documentation

The documentation is written in [ReStructuredText](http://www.sphinx-doc.org/en/stable/rest.html), which needs to be built with [Sphinx](http://www.sphinx-doc.org/en/stable/index.html) before the changes will appear.

To install Sphinx:
```bash
$ pip install Sphinx
```

To build the docs:
```bash
$ cd docs/
$ make html
```

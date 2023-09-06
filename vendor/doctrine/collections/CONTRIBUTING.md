# Contribute to Doctrine

Thank you for contributing to Doctrine!

Before we can merge your Pull-Request here are some guidelines that you need to follow.
These guidelines exist not to annoy you, but to keep the code base clean,
unified and future proof.

## Coding Standard

We use the [Doctrine Coding Standard](https://github.com/doctrine/coding-standard).

## Unit-Tests

Please try to add a test for your pull-request.

* If you want to contribute new functionality add unit- or functional tests
  depending on the scope of the feature.

You can run the unit-tests by calling ``vendor/bin/phpunit`` from the root of the project.
It will run all the project tests.

In order to do that, you will need a fresh copy of doctrine/collections, and you
will have to run a composer installation in the project:

```sh
git clone git@github.com:doctrine/collections.git
cd collections
curl -sS https://getcomposer.org/installer | php --
./composer.phar install
```

## Github Actions

We automatically run your pull request through Github Actions against supported
PHP versions. If you break the tests, we cannot merge your code, so please make
sure that your code is working before opening up a Pull-Request.

## Getting merged

Please allow us time to review your pull requests. We will give our best to review
everything as fast as possible, but cannot always live up to our own expectations.

Thank you very much again for your contribution!

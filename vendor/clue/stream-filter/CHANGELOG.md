# Changelog

## 1.6.0 (2022-02-21)

*   Feature: Support PHP 8.1 release.
    (#45 by @clue)

*   Improve documentation to use fully-qualified function names.
    (#43 by @SimonFrings and #42 by @PaulRotmann)

*   Improve test suite and use GitHub actions for continuous integration (CI).
    (#39 and #40 by @SimonFrings)

## 1.5.0 (2020-10-02)

*   Feature: Improve performance by using global imports.
    (#38 by @clue)

*   Improve API documentation and add support / sponsorship info.
    (#30 by @clue and #35 by @SimonFrings)

*   Improve test suite and add `.gitattributes` to exclude dev files from exports.
    Prepare PHP 8 support, update to PHPUnit 9 and simplify test matrix.
    (#32 and #37 by @clue and #34 and #36 by @SimonFrings)

## 1.4.1 (2019-04-09)

*   Fix: Check if the function is declared before declaring it.
    (#23 by @Niko9911)

*   Improve test suite to also test against PHP 7.2 and
    add test for base64 encoding and decoding filters.
    (#22 by @arubacao and #25 by @Nyholm and @clue)

## 1.4.0 (2017-08-18)

*   Feature / Fix: The `fun()` function does not pass filter parameter `null`
    to underlying `stream_filter_append()` by default
    (#15 by @Nyholm)

    Certain filters (such as `convert.quoted-printable-encode`) do not accept
    a filter parameter at all. If no explicit filter parameter is given, we no
    longer pass a default `null` value.

    ```php
    $encode = Filter\fun('convert.quoted-printable-encode');
    assert('t=C3=A4st' === $encode('täst'));
    ```

*   Add examples and improve documentation
    (#13 and #20 by @clue and #18 by @Nyholm)

*   Improve test suite by adding PHPUnit to require-dev,
    fix HHVM build for now again and ignore future HHVM build errors,
    lock Travis distro so new future defaults will not break the build
    and test on PHP 7.1
    (#12, #14 and #19 by @clue and #16 by @Nyholm)

## 1.3.0 (2015-11-08)

*   Feature: Support accessing built-in filters as callbacks
    (#5 by @clue)

    ```php
    $fun = Filter\fun('zlib.deflate');

    $ret = $fun('hello') . $fun('world') . $fun();
    assert('helloworld' === gzinflate($ret));
    ```

## 1.2.0 (2015-10-23)

* Feature: Invoke close event when closing filter (flush buffer)
  (#9 by @clue)

## 1.1.0 (2015-10-22)

* Feature: Abort filter operation when catching an Exception
  (#10 by @clue)

* Feature: Additional safeguards to prevent filter state corruption
  (#7 by @clue)

## 1.0.0 (2015-10-18)

* First tagged release

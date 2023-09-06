<?php

namespace Clue\StreamFilter;

/**
 * Append a filter callback to the given stream.
 *
 * Each stream can have a list of filters attached.
 * This function appends a filter to the end of this list.
 *
 * If the given filter can not be added, it throws an `Exception`.
 *
 * The `$stream` can be any valid stream resource, such as:
 *
 * ```php
 * $stream = fopen('demo.txt', 'w+');
 * ```
 *
 * The `$callback` should be a valid callable function which accepts
 * an individual chunk of data and should return the updated chunk:
 *
 * ```php
 * $filter = Clue\StreamFilter\append($stream, function ($chunk) {
 *     // will be called each time you read or write a $chunk to/from the stream
 *     return $chunk;
 * });
 * ```
 *
 * As such, you can also use native PHP functions or any other `callable`:
 *
 * ```php
 * Clue\StreamFilter\append($stream, 'strtoupper');
 *
 * // will write "HELLO" to the underlying stream
 * fwrite($stream, 'hello');
 * ```
 *
 * If the `$callback` accepts invocation without parameters,
 * then this signature will be invoked once ending (flushing) the filter:
 *
 * ```php
 * Clue\StreamFilter\append($stream, function ($chunk = null) {
 *     if ($chunk === null) {
 *         // will be called once ending the filter
 *         return 'end';
 *     }
 *     // will be called each time you read or write a $chunk to/from the stream
 *     return $chunk;
 * });
 *
 * fclose($stream);
 * ```
 *
 * > Note: Legacy PHP versions (PHP < 5.4) do not support passing additional data
 * from the end signal handler if the stream is being closed.
 *
 * If your callback throws an `Exception`, then the filter process will be aborted.
 * In order to play nice with PHP's stream handling,
 * the `Exception` will be transformed to a PHP warning instead:
 *
 * ```php
 * Clue\StreamFilter\append($stream, function ($chunk) {
 *     throw new \RuntimeException('Unexpected chunk');
 * });
 *
 * // raises an E_USER_WARNING with "Error invoking filter: Unexpected chunk"
 * fwrite($stream, 'hello');
 * ```
 *
 * The optional `$read_write` parameter can be used to only invoke the `$callback`
 * when either writing to the stream or only when reading from the stream:
 *
 * ```php
 * Clue\StreamFilter\append($stream, function ($chunk) {
 *     // will be called each time you write to the stream
 *     return $chunk;
 * }, STREAM_FILTER_WRITE);
 *
 * Clue\StreamFilter\append($stream, function ($chunk) {
 *     // will be called each time you read from the stream
 *     return $chunk;
 * }, STREAM_FILTER_READ);
 * ```
 *
 * This function returns a filter resource which can be passed to [`remove()`](#remove).
 *
 * > Note that once a filter has been added to stream, the stream can no longer be passed to
 * > [`stream_select()`](https://www.php.net/manual/en/function.stream-select.php)
 * > (and family).
 * >
 * > > Warning: stream_select(): cannot cast a filtered stream on this system in {file} on line {line}
 * >
 * > This is due to limitations of PHP's stream filter support, as it can no longer reliably
 * > tell when the underlying stream resource is actually ready.
 * > As an alternative, consider calling `stream_select()` on the unfiltered stream and
 * > then pass the unfiltered data through the [`fun()`](#fun) function.
 *
 * @param resource $stream
 * @param callable $callback
 * @param int $read_write
 * @return resource filter resource which can be used for `remove()`
 * @throws \Exception on error
 * @uses stream_filter_append()
 */
function append($stream, $callback, $read_write = STREAM_FILTER_ALL)
{
    $ret = @\stream_filter_append($stream, register(), $read_write, $callback);

    // PHP 8 throws above on type errors, older PHP and memory issues can throw here
    // @codeCoverageIgnoreStart
    if ($ret === false) {
        $error = \error_get_last() + array('message' => '');
        throw new \RuntimeException('Unable to append filter: ' . $error['message']);
    }
    // @codeCoverageIgnoreEnd

    return $ret;
}

/**
 * Prepend a filter callback to the given stream.
 *
 * Each stream can have a list of filters attached.
 * This function prepends a filter to the start of this list.
 *
 * If the given filter can not be added, it throws an `Exception`.
 *
 * ```php
 * $filter = Clue\StreamFilter\prepend($stream, function ($chunk) {
 *     // will be called each time you read or write a $chunk to/from the stream
 *     return $chunk;
 * });
 * ```
 *
 * This function returns a filter resource which can be passed to [`remove()`](#remove).
 *
 * Except for the position in the list of filters, this function behaves exactly
 * like the [`append()`](#append) function.
 * For more details about its behavior, see also the [`append()`](#append) function.
 *
 * @param resource $stream
 * @param callable $callback
 * @param int $read_write
 * @return resource filter resource which can be used for `remove()`
 * @throws \Exception on error
 * @uses stream_filter_prepend()
 */
function prepend($stream, $callback, $read_write = STREAM_FILTER_ALL)
{
    $ret = @\stream_filter_prepend($stream, register(), $read_write, $callback);

    // PHP 8 throws above on type errors, older PHP and memory issues can throw here
    // @codeCoverageIgnoreStart
    if ($ret === false) {
        $error = \error_get_last() + array('message' => '');
        throw new \RuntimeException('Unable to prepend filter: ' . $error['message']);
    }
    // @codeCoverageIgnoreEnd

    return $ret;
}

/**
 * Create a filter function which uses the given built-in `$filter`.
 *
 * PHP comes with a useful set of [built-in filters](https://www.php.net/manual/en/filters.php).
 * Using `fun()` makes accessing these as easy as passing an input string to filter
 * and getting the filtered output string.
 *
 * ```php
 * $fun = Clue\StreamFilter\fun('string.rot13');
 *
 * assert('grfg' === $fun('test'));
 * assert('test' === $fun($fun('test'));
 * ```
 *
 * Please note that not all filter functions may be available depending
 * on installed PHP extensions and the PHP version in use.
 * In particular, [HHVM](https://hhvm.com/) may not offer the same filter functions
 * or parameters as Zend PHP.
 * Accessing an unknown filter function will result in a `RuntimeException`:
 *
 * ```php
 * Clue\StreamFilter\fun('unknown'); // throws RuntimeException
 * ```
 *
 * Some filters may accept or require additional filter parameters – most
 * filters do not require filter parameters.
 * If given, the optional `$parameters` argument will be passed to the
 * underlying filter handler as-is.
 * In particular, note how *not passing* this parameter at all differs from
 * explicitly passing a `null` value (which many filters do not accept).
 * Please refer to the individual filter definition for more details.
 * For example, the `string.strip_tags` filter can be invoked like this:
 *
 * ```php
 * $fun = Clue\StreamFilter\fun('string.strip_tags', '<a><b>');
 *
 * $ret = $fun('<b>h<br>i</b>');
 * assert('<b>hi</b>' === $ret);
 * ```
 *
 * Under the hood, this function allocates a temporary memory stream, so it's
 * recommended to clean up the filter function after use.
 * Also, some filter functions (in particular the
 * [zlib compression filters](https://www.php.net/manual/en/filters.compression.php))
 * may use internal buffers and may emit a final data chunk on close.
 * The filter function can be closed by invoking without any arguments:
 *
 * ```php
 * $fun = Clue\StreamFilter\fun('zlib.deflate');
 *
 * $ret = $fun('hello') . $fun('world') . $fun();
 * assert('helloworld' === gzinflate($ret));
 * ```
 *
 * The filter function must not be used anymore after it has been closed.
 * Doing so will result in a `RuntimeException`:
 *
 * ```php
 * $fun = Clue\StreamFilter\fun('string.rot13');
 * $fun();
 *
 * $fun('test'); // throws RuntimeException
 * ```
 *
 * > Note: If you're using the zlib compression filters, then you should be wary
 * about engine inconsistencies between different PHP versions and HHVM.
 * These inconsistencies exist in the underlying PHP engines and there's little we
 * can do about this in this library.
 * [Our test suite](tests/) contains several test cases that exhibit these issues.
 * If you feel some test case is missing or outdated, we're happy to accept PRs! :)
 *
 * @param string $filter     built-in filter name. See stream_get_filters() or http://php.net/manual/en/filters.php
 * @param mixed  $parameters (optional) parameters to pass to the built-in filter as-is
 * @return callable a filter callback which can be append()'ed or prepend()'ed
 * @throws \RuntimeException on error
 * @link http://php.net/manual/en/filters.php
 * @see stream_get_filters()
 * @see append()
 */
function fun($filter, $parameters = null)
{
    $fp = \fopen('php://memory', 'w');
    if (\func_num_args() === 1) {
        $filter = @\stream_filter_append($fp, $filter, \STREAM_FILTER_WRITE);
    } else {
        $filter = @\stream_filter_append($fp, $filter, \STREAM_FILTER_WRITE, $parameters);
    }

    if ($filter === false) {
        \fclose($fp);
        $error = \error_get_last() + array('message' => '');
        throw new \RuntimeException('Unable to access built-in filter: ' . $error['message']);
    }

    // append filter function which buffers internally
    $buffer = '';
    append($fp, function ($chunk) use (&$buffer) {
        $buffer .= $chunk;

        // always return empty string in order to skip actually writing to stream resource
        return '';
    }, \STREAM_FILTER_WRITE);

    $closed = false;

    return function ($chunk = null) use ($fp, $filter, &$buffer, &$closed) {
        if ($closed) {
            throw new \RuntimeException('Unable to perform operation on closed stream');
        }
        if ($chunk === null) {
            $closed = true;
            $buffer = '';
            \fclose($fp);
            return $buffer;
        }
        // initialize buffer and invoke filters by attempting to write to stream
        $buffer = '';
        \fwrite($fp, $chunk);

        // buffer now contains everything the filter function returned
        return $buffer;
    };
}

/**
 * Remove a filter previously added via `append()` or `prepend()`.
 *
 * ```php
 * $filter = Clue\StreamFilter\append($stream, function () {
 *     // …
 * });
 * Clue\StreamFilter\remove($filter);
 * ```
 *
 * @param resource $filter
 * @return bool true on success or false on error
 * @throws \RuntimeException on error
 * @uses stream_filter_remove()
 */
function remove($filter)
{
    if (@\stream_filter_remove($filter) === false) {
        // PHP 8 throws above on type errors, older PHP and memory issues can throw here
        $error = \error_get_last();
        throw new \RuntimeException('Unable to remove filter: ' . $error['message']);
    }
}

/**
 * Registers the callback filter and returns the resulting filter name
 *
 * There should be little reason to call this function manually.
 *
 * @return string filter name
 * @uses CallbackFilter
 */
function register()
{
    static $registered = null;
    if ($registered === null) {
        $registered = 'stream-callback';
        \stream_filter_register($registered, __NAMESPACE__ . '\CallbackFilter');
    }
    return $registered;
}

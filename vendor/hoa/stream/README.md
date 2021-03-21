<p align="center">
  <img src="https://static.hoa-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/hoaproject/stream"><img src="https://img.shields.io/travis/hoaproject/stream/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/hoaproject/stream?branch=master"><img src="https://img.shields.io/coveralls/hoaproject/stream/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/hoa/stream"><img src="https://img.shields.io/packagist/dt/hoa/stream.svg" alt="Packagist" /></a>
  <a href="https://hoa-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/hoa/stream.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# Hoa\Stream

[![Help on IRC](https://img.shields.io/badge/help-%23hoaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#hoaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/hoaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.hoa-project.net/Documentation/Library/Stream)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/hoaproject/stream)

This library is a high-level abstraction over PHP streams. It
includes:

  * Stream manipulations: Open, close, auto-close, timeout, blocking
    mode, buffer size, metadata etc.,
  * Stream notifications: Depending of the stream wrapper, the
    supported listeners are the following: `authrequire`,
    `authresult`, `complete`, `connect`, `failure`, `mimetype`,
    `progress`, `redirect`, `resolve`, and `size`,
  * Context: Allow to pass options and parameters to the stream
    wrappers, for instance HTTP headers,
  * Filter: A function that sits between the source and the
    destination of a stream, useful for instance to encrypt/decrypt a
    data on-the-fly, or for more advanced tricks like instrumentation,
  * Wrapper: Declare user-defined protocols that will naturally be
    handled by the PHP standard library (like `fopen`,
    `stream_get_contents` etc.),
  * Interfaces: One interface per capability a stream can offer.

This library is the foundation of several others, e.g.
[`Hoa\File`](https://central.hoa-project.net/Resource/Library/File) or
[`Hoa\Socket`](https://central.hoa-project.net/Resource/Library/Socket)
(and so
[`Hoa\Websocket`](https://central.hoa-project.net/Resource/Library/Websocket)).

[Learn more](https://central.hoa-project.net/Documentation/Library/Stream).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`hoa/stream`](https://packagist.org/packages/hoa/stream):

```sh
$ composer require hoa/stream '~1.0'
```

For more installation procedures, please read [the Source
page](https://hoa-project.net/Source.html).

## Testing

Before running the test suites, the development dependencies must be installed:

```sh
$ composer install
```

Then, to run all the test suites:

```sh
$ vendor/bin/hoa test:run
```

For more information, please read the [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html).

## Quick usage

As a quick overview, we propose to discover what `Hoa\Stream` provides
in term of interfaces, i.e. stream capabilities. This is almost the
most important part of this library. Then, how to define a stream,
followed by how to use stream contexts. Events, listeners and
notifications will be detailed in the next section. Finally, wrappers
and filters are detailed in the last sections.

### Interfaces, aka stream capabilities

This library defines several interfaces representing important stream
capabilities. This is very useful when designing a function, or a
library, working with streams. It ensures the stream is typed and
offers certain capabilities. The interfaces are declared in the
`Hoa\Stream\IStream` namespace:

  * `In`, to read from a stream, provides `read`, `readInteger`,
    `readLine`, `readAll`, `eof` etc.,
  * `Out`, to write onto a stream, provides `write`, `writeArray`,
    `writeLine`, `truncate` etc.,
  * `Bufferable`, for streams with at least one internal buffer,
    provides `newBuffer`, `flush`, `getBufferLevel` etc.,
  * `Touchable`, for “touchable” streams, provides `touch`, `copy`,
    `move`, `delete`, `changeGroup` etc.,
  * `Lockable`, to lock a stream, provides `lock` and several
    constants representing different kind of locks, like
    `LOCK_SHARED`, `LOCK_EXCLUSIVE`, `LOCK_NO_BLOCK` etc.,
  * `Pathable`, for path-based stream, provides `getBasename` and
    `getDirname`,
  * `Pointable`, to move the internal pointer of the stream if any,
    provides `rewind`, `seek` and `tell`,
  * `Statable`, to get statistics about a stream, provides `getSize`,
    `getStatistics`, `getATime`, `getCTime`, `isReadable` etc.,
  * `Structural`, for a structural stream, i.e. a stream acting like a
    tree, provides `selectRoot`, `selectAnyElements`,
    `selectElements`, `selectAdjacentSiblingElement`, `querySelector`
    etc.

Thus, if one only need to read from a stream, it will type the stream
with `Hoa\Stream\IStream\In`. It also allows an implementer to choose
what capabilities its stream will provide or not.

Finally, the highest interface is `Stream`, defining the `getStream`
method, that's all. That's the most undefined stream. All capabilities
must extend this interface.

### Define a concrete stream

The main `Hoa\Stream\Stream` class is abstract. Two method
implementations are left to the user: `_open` and : `_close`,
respectively to open a particular stream, and to close this particular
stream, for instance:

```php
class BasicFile extends Hoa\Stream\Stream
{
    protected function &_open($streamName, Hoa\Stream\Context $context = null)
    {
        if (null === $context) {
            $out = fopen($streamName, 'rb');
        } else {
            $out = fopen($streamName, 'rb', false, $context->getContext());
        }

        return $out;
    }

    protected function _close()
    {
        return fclose($this->getStream());
    }
}
```

Then, the most common usage will be:

```php
$file = new BasicFile('/path/to/file');
```

That's all. This stream has no capability yet. Let's implement the
`In` capability:

```php
class BasicFile extends Hoa\Stream\Stream implements Hoa\Stream\IStream\In
{
    // …

    public function read($length)
    {
        return fread($this->getStream(), max(1, $length));
    }

    // …
}
```

Other methods are left as an exercise to the reader. Thus, we are now
able to:

```php
$chunk = $file->read(42);
```

The `Stream` capability is already implemented by the
`Hoa\Stream\Stream` class.

### Contextual streams

A context is represented by the `Hoa\Stream\Context` class. It
represents a set of options and parameters for the stream.
[See the options and parameters for the `http://` stream wrapper](http://php.net/context.http)
as an example of possible ones. Thanks to context, this is possible to
add HTTP headers for instance, or to specify the proxy, the maximum
number of redirections etc. All these information are
options/parameters of the stream.

To use them, first let's define the context:

```php
$contextId = 'my_http_context';
$context   = Hoa\Stream\Context::getInstance($contextId);
$context->setOptions([
    // …
]);
```

And thus, we can ask a stream to use this context based on the chosen
context ID, like this:

```php
$basicFile = new BasicFile('/path/to/file', $contextId);
```

For the stream implementer, the `getOptions` and `getParameters`
methods on the `Hoa\Stream\Context` class will be useful to
respectively retrieve the options and the parameters, and acts
according to them.

The concept of _options_ and _parameters_ are defined by PHP itself.

### Events, listeners, and notifications

A stream has some events, and several listeners. So far, listeners
mostly represent “stream notifications”.

2 events are registered: `hoa://Event/Stream/<streamName>` and
`hoa://Event/Stream/<streamName>:close-before`. Thus, for instance, to
execute a function before the `/path/to/file` stream closes, one
will write:

```php
Hoa\Event\Event::getEvent('hoa://Event/Stream//path/to/file:close-before')->attach(
    function (Hoa\Event\Bucket $bucket) {
        // do something!
    }
);
```

Remember that a stream is not necessarily a file. It can be a socket,
a WebSocket, a stringbuffer, any stream you have defined…
Consequently, this event can be used in very different manner for
various scenario, like logging things, closing related resources,
firing another event… There is no rule. The observed stream is still
opened, and can theoritically still be used.

This event is fired when calling the `Hoa\Stream\Stream::close`
method.

Now let's move on to listeners. To register a listener, we must create
an instance of our stream without opening it. This action is called
“deferred opening”. We can control the opening time with the third
argument of the default `Hoa\Stream\Stream` constructor; `true` to
defer the opening, like:

```php
$file = new BasicFile('/path/to/file', null, true);
// do something
$file->open();
```

Passing `null` as a second argument means: No context. Note that we
must manually call the `open` method to open the stream then. Between
the stream instanciation and the stream opening, we can attach new
listeners.

Depending of the stream implementation, different listeners will be
fired. The term “listener” is the one used everywhere in Hoa, but PHP
—in the context of stream— refers to them as notifications. Let's take
an example with an HTTP stream:

```php
$basic = new BasicFile(
    'https://hoa-project.net/', // stream name
    null,                       // context ID
    true                        // defere opening
);
$basic->on(
    'connect',
    function (Hoa\Event\Bucket $bucket) {
        echo 'Connected', "\n";
    }
);
$basic->on(
    'redirect',
    function (Hoa\Event\Bucket $bucket) {
        echo 'Redirection to ', $bucket->getData()['message'], "\n";
    }
);
$basic->on(
    'mimetype',
    function (Hoa\Event\Bucket $bucket) {
        echo 'MIME-Type is ', $bucket->getData()['message'], "\n";
    }
);
$basic->on(
    'size',
    function (Hoa\Event\Bucket $bucket) {
        echo 'Size is ', $bucket->getData()['max'], "\n";
    }
);
$basic->on(
    'progress',
    function (Hoa\Event\Bucket $bucket) {
        echo 'Progressed, ', $bucket->getData()['transferred'], ' bytes downloaded', "\n";
    }
);

// Then open.
$basic->open();
```

You might see something like this:

```
Connected
MIME-Type is text/html; charset=UTF-8
Redirection to /En/
Connected
MIME-Type is text/html; charset=UTF-8
Progressed, … bytes downloaded
Progressed, … bytes downloaded
```

The exhaustive list of listeners is the following:

  * `authrequire`, when the authentication is required,
  * `authresult`, when the result of the authentication is known,
  * `complete`, when the stream is complete (meaning can vary a lot here),
  * `connect`, when the stream is connected (meaning can vary a lot here),
  * `failure`, when something unexpected occured,
  * `mimetype`, when the MIME-type of the stream is known,
  * `progress`, when there is significant progression,
  * `redirect`, when the stream is redirected to another stream,
  * `resolve`, when the stream is resolved (meaning can vary a lot here),
  * `size`, when the size of the stream is known.

All listener bucket data is an array containing the following pairs:

  * `code`, one of the `STREAM_NOTIFY_*` constant, which is basically
    the listener name
    (see [the documentation](http://php.net/stream.constants)),
  * `severity`, one of the `STREAM_NOTIFY_SEVERITY_*` constant:
    * `STREAM_NOTIFY_SEVERITY_INFO`, normal, non-error related,
      notification,
    * `STREAM_NOTIFY_SEVERITY_WARN`, non critical error condition,
      processing may continue,
    * `STREAM_NOTIFY_SEVERITY_ERR`, a critical error occurred,
      processing cannot continue.
  * `message`, a string containing most useful information,
  * `transferred`, amount of bytes already transferred,
  * `max`, total number of bytes to transfer.

This is possible for the stream implementer to add more
listeners. Please, take a look at
[the `Hoa\Event` library](https://central.hoa-project.net/Resource/Library/Event). Not
all listeners will be fired by all kind of streams.

### Wrappers

A stream wrapper allows to declare schemes, like `hoa://` or
`fortune://`. You can imagine adding your favorite online storage too,
`cloud://`. Any stream wrapper can be used with native standard PHP
functions, like `fopen`, `file_get_contents`, `mkdir`, `touch` etc. It
will be transparent for the user.

The `Hoa\Stream\Wrapper\Wrapper` class holds all methods to
`register`, `unregister`, and `restore` wrappers. The `isRegistered`
and `getRegistered` methods are also helpful. A wrapper is represented by a class:

```php
Hoa\Stream\Wrapper\Wrapper::register('tmp', Tmp::class);
```

A wrapper must implement the `Hoa\Stream\Wrapper\IWrapper\IWrapper`
interface. It is a combination of two other interfaces in the same
namespace: `Stream` and `File`.

The `Stream` interface requires to implement several methods related to a stream, such as:

  * `stream_open`,
  * `stream_close`,
  * `stream_cast`,
  * `stream_eof`,
  * `stream_flush`,
  * `stream_lock`,
  * `stream_metadata`,
  * `stream_read`,
  * `stream_write`,
  * `stream_seek`,
  * `stream_tell`,
  * `stream_stat`,
  * etc.

The API provides all required information.

The `File` interface requires to implement other methods related to stream acting as a file, such as:

  * `mkdir`,
  * `dir_opendir`,
  * `dir_closedir`,
  * `dir_readdir`,
  * `rename`,
  * `unlink`,
  * etc.

An example of an implementation is the `hoa://` scheme in
[the `Hoa\Protocol` library](https://central.hoa-project.net/Resource/Library/Protocol).
It does not depend on this library to avoid dependencies, but the code
can be helpful.

### Filters

A stream is like a pipe, with an input, and an output. This is
possible to cut this pipe in two pieces, and insert a small part: A
filter. There are three types of filter, identified by constants on
the `Hoa\Stream\Filter\Filter` class:

  1. `Filter::READ` when the filter applies for reading operations,
  2. `Filter::WRITE` when the filter applies for writing operations,
  3. `Filter::READ_AND_WRITE` when both.

This class allows to `register` or `remove` filters. A filter takes
the form of a class extending the `Hoa\Stream\Filter\Basic` filter,
and an associated name. This is not mandatory but highly encouraged.

Once a filter is registered, we can apply it on a stream by using its
name, with the `append` or `prepend` methods. You might guess that
several filters can be applied on a stream, in a specific order, like
“decrypt”, “unzip”, “transform to…”. In such a scenario, the order
matters.

Finally, we use the stream as usual. A stream is not necessarily an
instance of `Hoa\Stream`, it can be any PHP stream resources. Passing
an `Hoa\Stream` instance will obviously unwraps to its underlying PHP
stream resource.

Let's implement a filter that changes the content of the stream into
uppercase. We start by defining out filter:

```php
class ToUpper extends Hoa\Stream\Filter\Basic
{
    public function filter($in, $out, &$consumed, $closing)
    {
        $iBucket = new Hoa\Stream\Bucket($in);
        $oBucket = new Hoa\Stream\Bucket($out);

        while (false === $iBucket->eob()) {
            $consumed += $iBucket->getLength();

            $iBucket->setData(strtoupper($iBucket->getData()));
            $oBucket->append($iBucket);
        }

        unset($iBucket);
        unset($oBucket);

        return parent::PASS_ON;
    }
}
```

Great. Now let's register our filter under a specific name:

```php
$filterName = 'toupper';
Hoa\Stream\Filter::register($filterName, ToUpper::class);
```

Then, we must apply the filter on a specific stream, so let's open a
stream, and append the filter:

```php
$file = new Hoa\File\Read(__FILE__);
Hoa\Stream\Filter::append($file, $filterName, Hoa\Stream\Filter::READ);
```

This filter has been applied for reading operations only. So we will
see its effect when reading on our stream, let's do it:

``` php
echo $file->readAll();
```

You will see everything in ASCII uppercase.

A filter is a low-level stream API. It integrates with all kind of
streams. And this is a very powerful tool. We mentionned some usages
like decrypt, transform to, unzip… Actually, PHP comes with certain
standard filters, like: `string.toupper`, `string.tolower`, `dechunk`,
`zlib.*`, `bzip2.*`, `convert.iconv.*` etc. The
`Hoa\Stream\Filter\Filter::getRegistered` method will provide the list
of all registered filters.

The `Hoa\Stream\Filter\LateComputed` class is a special filter. It
calls its public `compute` method when the stream reaches its end. So
by extending this filter, you can override the `compute` method and
works on the `_buffer` attribute. This buffer contains the whole
content of the stream. This is really a buffer. Why would it be
useful? For instance if you are reading a PHP file, you can transform
the source code on-the-fly by using a parser —for instance— and
rewrite parts of the file. This technique is particularily useful to
instrument codes (adding some probes).

This is also possible to auto-apply a filter with… a wrapper! For
example the `instrument://` wrapper can prepend a filter to the stream
being opened with the `stream_open` method (from the
`Hoa\Stream\Wrapper\IWrapper\Stream` interface).

Possibilities are numerous.

### Other operations

There are more to cover. `Hoa\Stream` supports composite streams (with
the `Hoa\Stream\Composite` abstract class), i.e. streams embedding
other streams, like
[the `Hoa\Xml` library](https://central.hoa-project.net/Resource/Library/Xml).
An XML stream reads and writes from another inner stream (a file, a
socket, or anything else).
[The `Hoa\Stringbuffer` library](https://central.hoa-project.net/Resource/Library/Stringbuffer)
allows a string to be manipulated with a stream API, so the stream
content is written on the disk. Stream capabiilities are not the same
than `Hoa\File` as you might guess.

## Documentation

The
[hack book of `Hoa\Stream`](https://central.hoa-project.net/Documentation/Library/Stream) contains
detailed information about how to use this library and how it works.

To generate the documentation locally, execute the following commands:

```sh
$ composer require --dev hoa/devtools
$ vendor/bin/hoa devtools:documentation --open
```

More documentation can be found on the project's website:
[hoa-project.net](https://hoa-project.net/).

## Getting help

There are mainly two ways to get help:

  * On the [`#hoaproject`](https://webchat.freenode.net/?channels=#hoaproject)
    IRC channel,
  * On the forum at [users.hoa-project.net](https://users.hoa-project.net).

## Contribution

Do you want to contribute? Thanks! A detailed [contributor
guide](https://hoa-project.net/Literature/Contributor/Guide.html) explains
everything you need to know.

## License

Hoa is under the New BSD License (BSD-3-Clause). Please, see
[`LICENSE`](https://hoa-project.net/LICENSE) for details.

## Related projects

The following projects are using this library:

  * [Marvirc](https://github.com/Hywan/Marvirc), A dead simple,
    extremely modular and blazing fast IRC bot,
  * [WellCommerce](http://wellcommerce.org/), Modern e-commerce engine
    built on top of Symfony 3 full-stack framework,
  * And of course many Hoa's libraries.

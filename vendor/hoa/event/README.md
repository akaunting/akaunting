<p align="center">
  <img src="https://static.hoa-project.net/Image/Hoa.svg" alt="Hoa" width="250px" />
</p>

---

<p align="center">
  <a href="https://travis-ci.org/hoaproject/event"><img src="https://img.shields.io/travis/hoaproject/event/master.svg" alt="Build status" /></a>
  <a href="https://coveralls.io/github/hoaproject/event?branch=master"><img src="https://img.shields.io/coveralls/hoaproject/event/master.svg" alt="Code coverage" /></a>
  <a href="https://packagist.org/packages/hoa/event"><img src="https://img.shields.io/packagist/dt/hoa/event.svg" alt="Packagist" /></a>
  <a href="https://hoa-project.net/LICENSE"><img src="https://img.shields.io/packagist/l/hoa/event.svg" alt="License" /></a>
</p>
<p align="center">
  Hoa is a <strong>modular</strong>, <strong>extensible</strong> and
  <strong>structured</strong> set of PHP libraries.<br />
  Moreover, Hoa aims at being a bridge between industrial and research worlds.
</p>

# Hoa\Event

[![Help on IRC](https://img.shields.io/badge/help-%23hoaproject-ff0066.svg)](https://webchat.freenode.net/?channels=#hoaproject)
[![Help on Gitter](https://img.shields.io/badge/help-gitter-ff0066.svg)](https://gitter.im/hoaproject/central)
[![Documentation](https://img.shields.io/badge/documentation-hack_book-ff0066.svg)](https://central.hoa-project.net/Documentation/Library/Event)
[![Board](https://img.shields.io/badge/organisation-board-ff0066.svg)](https://waffle.io/hoaproject/event)

This library allows to use events and listeners in PHP. This is an observer
design-pattern implementation.

[Learn more](https://central.hoa-project.net/Documentation/Library/Event).

## Installation

With [Composer](https://getcomposer.org/), to include this library into
your dependencies, you need to
require [`hoa/event`](https://packagist.org/packages/hoa/event):

```sh
$ composer require hoa/event '~1.0'
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

We propose a quick overview of how to use events and listeners.

### Events

An event is:
  * **Asynchronous** when registering, because the observable may not exist yet
    while observers start to observe,
  * **Anonymous** when using, because the observable has no idea how many and
    what observers are observing,
  * It aims at a **large** diffusion of data through isolated components.
    Wherever is the observable, we can observe its data.

In Hoa, an event channel has the following form:
`hoa://Event/LibraryName/AnId:pseudo-class#anAnchor`. For instance, the
`hoa://Event/Exception` channel contains all exceptions that have been thrown.
The `hoa://Event/Stream/StreamName:close-before` contains all streams that are
about to close. Thus, the following example will observe all thrown exceptions:

```php
Hoa\Event\Event::getEvent('hoa://Event/Exception')->attach(
    function (Hoa\Event\Bucket $bucket) {
        var_dump(
            $bucket->getSource(),
            $bucket->getData()
        );
    }
);
```

Because `attach` expects a callable and because Hoa's callable implementation is
smart, we can directly attach a stream to an event, like:

```php
Hoa\Event\Event::getEvent('hoa://Event/Exception')->attach(
    new Hoa\File\Write('Foo.log')
);
```

This way, all exceptions will be printed on the `Foo.log` file.

### Listeners

Contrary to an event, a listener is:
  * **Synchronous** when registering, because the observable must exist before
    observers can observe,
  * **Identified** when using, because the observable knows how many observers
    are observing,
  * It aims at a **close** diffusion of data. The observers must have an access
    to the observable to observe.

The `Hoa\Event\Listenable` interface requires the `on` method to be present to
register a listener to a listener ID. For instance, the following example
listens the `message` listener ID, i.e. when a message is received by the
WebSocket server, the closure is executed:

```php
$server = new Hoa\Websocket\Server(…);
$server->on('message', function (Hoa\Event\Bucket $bucket) {
    var_dump(
        $bucket->getSource(),
        $bucket->getData()
    );
});
```

## Documentation

The
[hack book of `Hoa\Event`](https://central.hoa-project.net/Documentation/Library/Event) contains
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

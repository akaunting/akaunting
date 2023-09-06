# CHANGELOG

## 3.21.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.21.0.

### Features

- Add `Sentry::captureCheckIn()` [(#1573)](https://github.com/getsentry/sentry-php/pull/1573)

  Sending check-ins from the SDK is now simplified.

  ```php
  $checkInId = Sentry\captureCheckIn(
      slug: 'monitor-slug',
      status: CheckInStatus::inProgress()
  );


  // do something

  Sentry\captureCheckIn(
      checkInId: $checkInId,
      slug: 'monitor-slug',
      status: CheckInStatus::ok()
  );
  ```

  You can also pass in a `monitorConfig` object as well as the `duration`.

- Undeprecate the `tags` option [(#1561)](https://github.com/getsentry/sentry-php/pull/1561)

  You can now set tags that are applied to each event when calling `Sentry::init()`.

  ```php
  Sentry\init([
      'tags' => [
          'foo' => 'bar',
      ],
  ])
  ```

- Apply the `prefixes`option to profiling frames [(#1568)](https://github.com/getsentry/sentry-php/pull/1568)

  If you added the `prefixes` option when calling `Sentry::init()`, this option will now also apply to profile frames.

  ```php
  Sentry\init([
      'prefixes' => ['/var/www/html'],
  ])
  ```

### Misc

- Deduplicate profile stacks and frames [(#1570)](https://github.com/getsentry/sentry-php/pull/1570)

  This will decrease the payload size of the `profile` event payload.

- Add the transaction's sampling decision to the trace envelope header [(#1562)](https://github.com/getsentry/sentry-php/pull/1562)

## 3.20.1

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.20.1.

### Bug Fixes

- Use the result of `isTracingEnabled()` to determine the behaviour of `getBaggage()` and `getTraceparent()` [(#1555)](https://github.com/getsentry/sentry-php/pull/1555)

### Misc

- Always return a `TransactionContext` from `continueTrace()` [(#1556)](https://github.com/getsentry/sentry-php/pull/1556)

## 3.20.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.20.0.

### Features

- Tracing without Performance [(#1516)](https://github.com/getsentry/sentry-php/pull/1516)

  You can now set up distributed tracing without the need to use the performance APIs.
  This allows you to connect your errors that hail from other Sentry instrumented applications to errors in your PHP application.

  To continue a trace, fetch the incoming Sentry tracing headers and call `\Sentry\continueTrace()` as early as possible in the request cycle.

  ```php
  $sentryTraceHeader = $request->getHeaderLine('sentry-trace');
  $baggageHeader = $request->getHeaderLine('baggage');

  continueTrace($sentryTraceHeader, $baggageHeader);
  ```

  To continue a trace outward, you may attach the Sentry tracing headers to any HTTP client request.
  You can fetch the required header values by calling `\Sentry\getBaggage()` and `\Sentry\getTraceparent()`.

- Upserting Cron Monitors [(#1511)](https://github.com/getsentry/sentry-php/pull/1511)

  You can now create and update your Cron Monitors programmatically with code.
  Read more about this in our [docs](https://docs.sentry.io/platforms/php/crons/#upserting-cron-monitors).

## 3.19.1

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.19.1.

### Bug Fixes

- Use HTTP/1.1 when compression is enabled [(#1542)](https://github.com/getsentry/sentry-php/pull/1542)

## 3.19.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.19.0.

### Misc

- Add support for `guzzlehttp/promises` v2 [(#1536)](https://github.com/getsentry/sentry-php/pull/1536)

## 3.18.2

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.18.2.

### Bug Fixes

- Require php-http/message-factory [(#1534)](https://github.com/getsentry/sentry-php/pull/1534)

## 3.18.1

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.18.1.

### Bug Fixes

- Guard against empty profiles [(#1528)](https://github.com/getsentry/sentry-php/pull/1528)
- Ignore empty context values [(#1529)](https://github.com/getsentry/sentry-php/pull/1529)

## 3.18.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.18.0.

### Features

- Add `TransactionContext::fromEnvironment` [(#1519)](https://github.com/getsentry/sentry-php/pull/1519)

### Misc

- Sent all events to the `/envelope` endpoint if tracing is enabled [(#1518)](https://github.com/getsentry/sentry-php/pull/1518)
- Attach the Dynamic Sampling Context to error events [(#1522)](https://github.com/getsentry/sentry-php/pull/1522)

## 3.17.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.17.0.

### Features

- Add `ignore_exceptions` & `ignore_transactions` options [(#1503)](https://github.com/getsentry/sentry-php/pull/1503)
  
  We deprecated the [IgnoreErrorsIntegration](https://docs.sentry.io/platforms/php/integrations/#ignoreerrorsintegration) in favor of this new option.
  The option will also take [previous exceptions](https://www.php.net/manual/en/exception.getprevious.php) into account.

  ```php
  \Sentry\init([
    'ignore_exceptions' => [BadThingsHappenedException::class],
  ]);
  ```

  To ignore a transaction being sent to Sentry, add its name to the config option.
  You can find the transaction name on the [Performance page](https://sentry.io/performance/).

  ```php
  \Sentry\init([
      'ignore_transactions' => ['GET /health'],
  ]);
  ```

### Misc

 - Bump `php-http/discovery` to `^1.15` [(#1504)](https://github.com/getsentry/sentry-php/pull/1504)

   You may need to allow the added composer plugin, introduced in `php-http/discovery v1.15.0`, to execute when running `composer update`.
   We previously pinned this package to version `<1.15`.
   Due to conflicts with other packages, we decided to lift this restriction.

## 3.16.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.16.0.
This release adds initial support for [Cron Monitoring](https://docs.sentry.io/product/crons/).

> **Warning**
> Cron Monitoring is currently in beta. Beta features are still in-progress and may have bugs. We recognize the irony.
> If you have any questions or feedback, please email us at crons-feedback@sentry.io, reach out via Discord (#cronjobs), or open an issue.

### Features

- Add inital support for Cron Monitoring [(#1467)](https://github.com/getsentry/sentry-php/pull/1467)
  
  You can use Cron Monitoring to monitor your cron jobs. No pun intended.

  Add the code below to your application or script that is invoked by your cron job.
  The first Check-In will let Sentry know that your job started, with the second Check-In reporting the outcome.

  ```php
  <?php

  $checkIn = new CheckIn(
      monitorSlug: '<your-monitor-slug>',
      status: CheckInStatus::inProgress(),
  );

  $event = Event::createCheckIn();
  $event->setCheckIn($checkIn);

  $this->hub->captureEvent($event);

  try {

      // do stuff

      $checkIn->setStatus(CheckInStatus::ok());
  } catch (Throwable $e) {
      $checkIn->setStatus(CheckInStatus::error());
  }

  $event = Event::createCheckIn();
  $event->setCheckIn($checkIn);

  $this->hub->captureEvent($event);
  ```

  If you only want to check if a cron did run, you may create a "Heartbeat" instead.
  Add the code below to your application or script that is invoked by your cron job.
  

  ```php
  <?php

  // do stuff

  $checkIn = new CheckIn(
      monitorSlug: '<your-monitor-slug>',
      status: CheckInStatus::ok(), // or - CheckInStatus::error()
      duration: 10, // optional - duration in seconds
  );

  $event = Event::createCheckIn();
  $event->setCheckIn($checkIn);

  $this->hub->captureEvent($event);
  ```

- Introduce a new `trace` helper function [(#1490)](https://github.com/getsentry/sentry-php/pull/1490)

  We made it a tad easier to add custom tracing spans to your application.

  ```php
  $spanContext = new SpanContext();
  $spanContext->setOp('function');
  $spanContext->setDescription('Soemthing to be traced');

  trace(
      function (Scope $scope) {
          // something to be traced
      },
      $spanContext,
  );
  ```

## 3.15.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.15.0.
This release adds initial support for [Profiling](https://docs.sentry.io/product/profiling/).

> **Warning**
> Profiling is currently in beta. Beta features are still in-progress and may have bugs. We recognize the irony.
> If you have any questions or feedback, please email us at profiling@sentry.io, reach out via Discord (#profiling), or open an issue.

Profiling is only available on Sentry SaaS (sentry.io). Support for Sentry self-hosted is planned once Profiling is released into GA.

### Features

- Add initial support for profiling [(#1477)](https://github.com/getsentry/sentry-php/pull/1477)

  Under the hood, we're using Wikipedia's sampling profiler [Excimer](https://github.com/wikimedia/mediawiki-php-excimer).
  We chose this profiler for its low overhead and for being used in production by one of the largest PHP-powered websites in the world.

  Excimer works with PHP 7.2 and up, for PHP 8.2 support, make sure to use Excimer version 1.1.0.

  There is currently no support for either Windows or macOS.

  You can install Excimer via your operating systems package manager.

  ```bash
  apt-get install php-excimer
  ```

  If no suitable version is available, you may build Excimer from source.

  ```bash
  git clone https://github.com/wikimedia/mediawiki-php-excimer.git

  cd excimer/
  phpize && ./configure && make && sudo make install
  ```

  Depending on your environment, you may need to enable the Excimer extension afterward.

  ```bash
  phpenmod -s fpm excimer
  # or
  phpenmod -s apache2 excimer
  ```

  Once the extension is installed, you may enable profiling by adding the new `profiles_sample_rate` config option to your `Sentry::init` method.

  ```php
  \Sentry\init([
      'dsn' => '__DSN__',
      'traces_sample_rate' => 1.0,
      'profiles_sample_rate' => 1.0,
  ]);
  ```

  Profiles are being sampled in relation to your `traces_sample_rate`.

  Please note that the profiler is started inside transactions only. If you're not using our [Laravel](https://github.com/getsentry/sentry-laravel) or [Symfony](https://github.com/getsentry/sentry-symfony) SDKs, you may need to manually add transactions to your application as described [here](https://docs.sentry.io/platforms/php/performance/instrumentation/custom-instrumentation/).

  #### Other things you should consider:

  - The current sample rate of the profiler is set to 101Hz (every ~10ms). A minimum of two samples is required for a profile being sent, hence requests that finish in less than ~20ms won't hail any profiles.
  - The maximum duration of a profile is 30s, hence we do not recommend enabling the extension in an CLI environment.
  - By design, the profiler will take samples at the end of any userland functions. You may see long sample durations on tasks like HTTP client requests and DB queries.
    You can read more about Excimer's architecture [here](https://techblog.wikimedia.org/2021/03/03/profiling-php-in-production-at-scale/).

## 3.14.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.14.0.

### Features

- Add a new `enable_tracing: true/false` option, an alternative for `traces_sample_rate: 1.0/null` [(#1458)](https://github.com/getsentry/sentry-php/pull/1458)

### Bug Fixes

- Fix missing keys in the request body [(#1470)](https://github.com/getsentry/sentry-php/pull/1470)
- Add support for partial JSON encoding [(#1481)](https://github.com/getsentry/sentry-php/pull/1481)
- Prevent calling *magic methods* when retrieving the ID from an object [(#1483)](https://github.com/getsentry/sentry-php/pull/1483)
- Only serialize scalar object IDs [(#1485)](https://github.com/getsentry/sentry-php/pull/1485)

### Misc

- The SDK is now licensed under MIT [(#1471)](https://github.com/getsentry/sentry-php/pull/1471)
  - Read more about Sentry's licensing [here](https://open.sentry.io/licensing/).
- Deprecate `Client::__construct` `$serializer` argument. It is currently un-used [(#1482)](https://github.com/getsentry/sentry-php/pull/1482)

## 3.13.1

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.13.1.

### Bug Fixes

- Sanatize HTTP client spans & breadcrumbs [(#1453)](https://github.com/getsentry/sentry-php/pull/1453)
- Pin php-http/discovery to `< 1.15` to disable some unwanted side-effect introduced in this new minor version [(#1464)](https://github.com/getsentry/sentry-php/pull/1464)

## 3.13.0

The Sentry SDK team is happy to announce the immediate availability of Sentry PHP SDK v3.13.0.

### Features

- Object IDs are now automatically serialized as part of a stack trace frame [(#1443)](https://github.com/getsentry/sentry-php/pull/1443)
  - If `Obj::getID()` or `Obj->id` is accessible, this value will be displayed inside the stack trace frame on the issue details page.
    To attach local variables to your stack trace, make sure `zend.exception_ignore_arg: 0` is set in your `php.ini`.
    See https://docs.sentry.io/platforms/php/troubleshooting/#missing-variables-in-stack-traces

- Add more functionality to the `ExceptionMechanism::class` [(#1450)](https://github.com/getsentry/sentry-php/pull/1450)
  - Attach arbitrary data
    ```php
    $hint = EventHint::fromArray([
        'exception' => $exception,
        'mechanism' => new ExceptionMechanism(
            ExceptionMechanism::TYPE_GENERIC,
            false,
            [
                'key' => 'value',
                //...
            ],
        ),
    ]);
    captureEvent(Event::createEvent(), $hint);
    ```
    Learn more about the interface of the `ExceptionMechanism` on https://develop.sentry.dev/sdk/event-payloads/exception/#exception-mechanism
  - Access or mutate `ExceptionMechanism::data` via `ExceptionMechanism::getData()` and `ExceptionMechanism::setData()`
  - If an exception contains a user-provided `code`, the value will be serialized into the event and displayed on the issues details page.
    ```php
    throw new \Exception('Oh no!', 123);
    ```

## 3.12.1 (2023-01-12)

- fix: Allow `null` on `getTracesSampleRate` and `setTracesSampleRate` in `Options` class (#1441)

## 3.12.0 (2022-11-22)

- feat: Add `before_send_transaction` option (#1424)
- fix: Set `traces_sample_rate` to `null` by default (#1428)

## 3.11.0 (2022-10-25)

- fix: Only include the transaction name to the DSC if it has good quality (#1410)
- ref: Enable the ModulesIntegration by default (#1415)
- ref: Expose the ExceptionMechanism through the event hint (#1416)

## 3.10.0 (2022-10-19)

- ref: Add correct `never` option for `max_request_body_size` (#1397)
  - Deprecate `max_request_body_size.none` in favour of `max_request_body_size.never`
- fix: Sampling now correctly takes in account the parent sampling decision if available instead of always being `false` when tracing is disabled (#1407)

## 3.9.1 (2022-10-11)

- fix: Suppress errors on is_callable (#1401)

## 3.9.0 (2022-10-05)

- feat: Add `trace_propagation_targets` option (#1396)
- feat: Expose a function to retrieve the URL of the CSP endpoint (#1378)
- feat: Add support for Dynamic Sampling (#1360)
  - Add `segment` to `UserDataBag`
  - Add `TransactionSource`, to set information about the transaction name via `TransactionContext::setSource()` (#1382)
  - Deprecate `TransactionContext::fromSentryTrace()` in favor of `TransactionContext::fromHeaders()`

## 3.8.1 (2022-09-21)

- fix: Use constant for the SDK version (#1374)
- fix: Do not throw an TypeError on numeric HTTP headers (#1370)

## 3.8.0 (2022-09-05)

- Add `Sentry\Monolog\BreadcrumbHandler`, a Monolog handler to allow registration of logs as breadcrumbs (#1199)
- Do not setup any error handlers if the DSN is null (#1349)
- Add setter for type on the `ExceptionDataBag` (#1347)
- Drop symfony/polyfill-uuid in favour of a standalone implementation (#1346)

## 3.7.0 (2022-07-18)

- Fix `Scope::getTransaction()` so that it returns also unsampled transactions (#1334)
- Set the event extras by taking the data from the Monolog record's extra (#1330)

## 3.6.1 (2022-06-27)

- Set the `sentry-trace` header when using the tracing middleware (#1331)

## 3.6.0 (2022-06-10)

- Add support for `monolog/monolog:^3.0` (#1321)
- Add `setTag` and `removeTag` public methods to `Event` for easier manipulation of tags (#1324)

## 3.5.0 (2022-05-19)

- Bump minimum version of `guzzlehttp/psr7` package to avoid [`CVE-2022-24775`](https://github.com/guzzle/psr7/security/advisories/GHSA-q7rv-6hp3-vh96) (#1305)
- Fix stripping of memory addresses from stacktrace frames of anonymous classes in PHP `>=7.4.2` (#1314)
- Set the default `send_attempts` to `0` (this disables retries) and deprecate the option. If you require retries you can increase the `send_attempts` option to the desired value. (#1312)
- Add `http_connect_timeout` and `http_timeout` client options (#1282)

## 3.4.0 (2022-03-14)

- Update Guzzle tracing middleware to meet the [expected standard](https://develop.sentry.dev/sdk/features/#http-client-integrations) (#1234)
- Add `toArray` public method in `PayloadSerializer` to be able to re-use Event serialization
- The `withScope` methods now return the callback's return value (#1263)
- Set the event extras by taking the data from the Monolog record's context (#1244)
- Make the `StacktraceBuilder` class part of the public API and add the `Client::getStacktraceBuilder()` method to build custom stacktraces (#1124)
- Support handling the server rate-limits when sending events to Sentry (#1291)
- Treat the project ID component of the DSN as a `string` rather than an `integer` (#1293)

## 3.3.7 (2022-01-19)

- Fix the serialization of a `callable` when the autoloader throws exceptions (#1280)

## 3.3.6 (2022-01-14)

- Optimize `Span` constructor and add benchmarks (#1274)
- Handle autoloader that throws an exception while trying to serialize a possible callable (#1276)

## 3.3.5 (2021-12-27)

- Bump the minimum required version of the `jean85/pretty-package-versions` package (#1267)

## 3.3.4 (2021-11-08)

- Avoid overwriting the error level set by the user on the event when capturing an `ErrorException` exception (#1251)
- Allow installing the project alongside Symfony `6.x` components (#1257)
- Run the test suite against PHP `8.1` (#1245)

## 3.3.3 (2021-10-04)

-  Fix fatal error in the `EnvironmentIntegration` integration if the `php_uname` function is disabled (#1243)

## 3.3.2 (2021-07-19)

- Allow installation of `guzzlehttp/psr7:^2.0` (#1225)
- Allow installation of `psr/log:^1.0|^2.0|^3.0` (#1229)

## 3.3.1 (2021-06-21)

- Fix missing collecting of frames's arguments when using `captureEvent()` without expliciting a stacktrace or an exception (#1223)

## 3.3.0 (2021-05-26)

- Allow setting a custom timestamp on the breadcrumbs (#1193)
- Add option `ignore_tags` to `IgnoreErrorsIntegration` in order to ignore exceptions by tags values (#1201)

## 3.2.2 (2021-05-06)

- Fix missing handling of `EventHint` in the `HubAdapter::capture*()` methods (#1206)

## 3.2.1 (2021-04-06)

- Changes behaviour of `error_types` option when not set: before it defaulted to `error_reporting()` statically at SDK initialization; now it will be evaluated each time during error handling to allow silencing errors temporarily (#1196)

## 3.2.0 (2021-03-03)

- Make the HTTP headers sanitizable in the `RequestIntegration` integration instead of removing them entirely (#1161)
- Deprecate the `logger` option (#1167)
- Pass the event hint from the `capture*()` methods down to the `before_send` callback (#1138)
- Deprecate the `tags` option, see the [docs](https://docs.sentry.io/platforms/php/guides/laravel/enriching-events/tags/) for other ways to set tags (#1174)
- Make sure the `environment` field is set to `production` if it has not been overridden explicitly (#1116)

## 3.1.5 (2021-02-18)

- Fix incorrect detection of silenced errors (by the `@` operator) (#1183)

## 3.1.4 (2021-02-02)

- Allow jean85/pretty-package-versions 2.0 (#1170)

## 3.1.3 (2021-01-25)

- Fix the fetching of the version of the SDK (#1169)
- Add the `$customSamplingContext` argument to `Hub::startTransaction()` and `HubAdapter::startTransaction()` to fix deprecations thrown in Symfony (#1176)

## 3.1.2 (2021-01-08)

- Fix unwanted call to the `before_send` callback with transaction events, use `traces_sampler` instead to filter transactions (#1158)
- Fix the `logger` option not being applied to the event object (#1165)
- Fix a bug that made some event attributes being overwritten by option config values when calling `captureEvent()` (#1148)

## 3.1.1 (2020-12-07)

- Add support for PHP 8.0 (#1087)
- Change the error handling for silenced fatal errors using `@` to use a mask check in order to be php 8 compatible (#1141)
- Update the `guzzlehttp/promises` package to the minimum required version compatible with PHP 8 (#1144)
- Update the `symfony/options-resolver` package to the minimum required version compatible with PHP 8 (#1144)

## 3.1.0 (2020-12-01)

- Fix capturing of the request body in the `RequestIntegration` integration (#1139)
- Deprecate `SpanContext::fromTraceparent()` in favor of `TransactionContext::fromSentryTrace()` (#1134)
- Allow setting custom data on the sampling context by passing it as 2nd argument of the `startTransaction()` function (#1134)
- Add setter for value on the `ExceptionDataBag` (#1100)
- Add `Scope::removeTag` method (#1126)

## 3.0.4 (2020-11-06)

- Fix stacktrace missing from payload for non-exception events (#1123)
- Fix capturing of the request body in the `RequestIntegration` integration when the stream is empty (#1119)

## 3.0.3 (2020-10-12)

- Fix missing source code excerpts for stacktrace frames whose absolute file path is equal to the file path (#1104)
- Fix requirements to construct a valid object instance of the `UserDataBag` class (#1108)

## 3.0.2 (2020-10-02)

- Fix use of the `sample_rate` option rather than `traces_sample_rate` when capturing a `Transaction` (#1106)

## 3.0.1 (2020-10-01)

- Fix use of `Transaction` instead of `Span` in the `GuzzleMiddleware` middleware (#1099)

## 3.0.0 (2020-09-28)

**Tracing API**

In this version we released API for Tracing. `\Sentry\startTransaction` is your entry point for manual instrumentation.
More information can be found in our [Performance](https://docs.sentry.io/platforms/php/performance/) docs.

**Breaking Change**: This version uses the [envelope endpoint](https://develop.sentry.dev/sdk/envelopes/). If you are
using an on-premise installation it requires Sentry version `>= v20.6.0` to work. If you are using
[sentry.io](https://sentry.io) nothing will change and no action is needed.

- [BC BREAK] Remove the deprecated code that made the `Hub` class a singleton (#1038)
- [BC BREAK] Remove deprecated code that permitted to register the error, fatal error and exception handlers at once (#1037)
- [BC BREAK] Change the default value for the `error_types` option from `E_ALL` to the value get from `error_reporting()` (#1037)
- [BC BREAK] Remove deprecated code to return the event ID as a `string` rather than an object instance from the transport, the client and the hub (#1036)
- [BC BREAK] Remove some deprecated methods from the `Options` class. (#1047)
- [BC BREAK] Remove the deprecated code from the `ModulesIntegration` integration (#1047)
- [BC BREAK] Remove the deprecated code from the `RequestIntegration` integration (#1047)
- [BC BREAK] Remove the deprecated code from the `Breadcrumb` class (#1047)
- [BC BREAK] Remove the deprecated methods from the `ClientBuilderInterface` interface and its implementations (#1047)
- [BC BREAK] The `Scope::setUser()` method now always merges the given data with the existing one instead of replacing it as a whole (#1047)
- [BC BREAK] Remove the `Context::CONTEXT_USER`, `Context::CONTEXT_RUNTIME`, `Context::CONTEXT_TAGS`, `Context::CONTEXT_EXTRA`, `Context::CONTEXT_SERVER_OS` constants (#1047)
- [BC BREAK] Use PSR-17 factories in place of the Httplug's ones and return a promise from the transport (#1066)
- [BC BREAK] The Monolog handler does not set anymore tags and extras on the event object (#1068)
- [BC BREAK] Remove the `UserContext`, `ExtraContext` and `Context` classes and refactor the `ServerOsContext` and `RuntimeContext` classes (#1071)
- [BC BREAK] Remove the `FlushableClientInterface` and the `ClosableTransportInterface` interfaces (#1079)
- [BC BREAK] Remove the `SpoolTransport` transport and all its related classes (#1080)
- Add the `EnvironmentIntegration` integration to gather data for the `os` and `runtime` contexts (#1071)
- Refactor how the event data gets serialized to JSON (#1077)
- Add `traces_sampler` option to set custom sample rate callback (#1083)
- [BC BREAK] Add named constructors to the `Event` class (#1085)
- Raise the minimum version of PHP to `7.2` and the minimum version of some dependencies (#1088)
- [BC BREAK] Change the `captureEvent` to only accept an instance of the `Event` class rather than also a plain array (#1094)
- Add Guzzle middleware to trace performance of HTTP requests (#1096)

## 3.0.0-beta1 (2020-09-03)

**Tracing API**

In this version we released API for Tracing. `\Sentry\startTransaction` is your entry point for manual instrumentation.
More information can be found in our [Performance](https://docs.sentry.io/product/performance/) docs or specific
[PHP SDK](https://docs.sentry.io/platforms/php/) docs.

**Breaking Change**: This version uses the [envelope endpoint](https://develop.sentry.dev/sdk/envelopes/). If you are
using an on-premise installation it requires Sentry version `>= v20.6.0` to work. If you are using
[sentry.io](https://sentry.io) nothing will change and no action is needed.

- [BC BREAK] Remove the deprecated code that made the `Hub` class a singleton (#1038)
- [BC BREAK] Remove deprecated code that permitted to register the error, fatal error and exception handlers at once (#1037)
- [BC BREAK] Change the default value for the `error_types` option from `E_ALL` to the value get from `error_reporting()` (#1037)
- [BC BREAK] Remove deprecated code to return the event ID as a `string` rather than an object instance from the transport, the client and the hub (#1036)
- [BC BREAK] Remove some deprecated methods from the `Options` class. (#1047)
- [BC BREAK] Remove the deprecated code from the `ModulesIntegration` integration (#1047)
- [BC BREAK] Remove the deprecated code from the `RequestIntegration` integration (#1047)
- [BC BREAK] Remove the deprecated code from the `Breadcrumb` class (#1047)
- [BC BREAK] Remove the deprecated methods from the `ClientBuilderInterface` interface and its implementations (#1047)
- [BC BREAK] The `Scope::setUser()` method now always merges the given data with the existing one instead of replacing it as a whole (#1047)
- [BC BREAK] Remove the `Context::CONTEXT_USER`, `Context::CONTEXT_RUNTIME`, `Context::CONTEXT_TAGS`, `Context::CONTEXT_EXTRA`, `Context::CONTEXT_SERVER_OS` constants (#1047)
- [BC BREAK] Use PSR-17 factories in place of the Httplug's ones and return a promise from the transport (#1066)
- [BC BREAK] The Monolog handler does not set anymore tags and extras on the event object (#1068)
- [BC BREAK] Remove the `UserContext`, `ExtraContext` and `Context` classes and refactor the `ServerOsContext` and `RuntimeContext` classes (#1071)
- [BC BREAK] Remove the `FlushableClientInterface` and the `ClosableTransportInterface` interfaces (#1079)
- [BC BREAK] Remove the `SpoolTransport` transport and all its related classes (#1080)
- Add the `EnvironmentIntegration` integration to gather data for the `os` and `runtime` contexts (#1071)
- Refactor how the event data gets serialized to JSON (#1077)

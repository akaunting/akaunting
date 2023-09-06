# Bugsnag-PHP Notifier Architecture

Version 1.0.0

Last updated 30/08/17

## Introduction
This document is one of a series describing the layout of the individual Bugsnag notifier libraries.  Their purpose is to make it easier to understand the layout and working logic involved in the notifiers for new contributors and users, and the preferred ways of extending and modifying said libraries.

## Dependencies
- [composer/ca-bundle](https://github.com/composer/ca-bundle)
- [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)

## Dev Dependencies
- [graham-campbell/testbench-core](https://github.com/GrahamCampbell/Laravel-TestBench-Core)
- [mockery/mockery](https://github.com/mockery/mockery)
- [mtdowling/burgomaster](https://github.com/mtdowling/Burgomaster)
- [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit)
- [php-mock/php-mock-phpunit](https://github.com/php-mock/php-mock-phpunit)

## Bugsnag-PHP Architecture
All code required to run the Bugsnag PHP notifier can be found within the `src` directory.

### The Client Object
The main Bugsnag object that will be used in applications for the purpose of catching and notify of errors is the client object.  This provides an API for the user to call on for the majority of the functions they will seek to utilise in the library, including: `notify`, `notify-exception`, `notify-error`, `leave-breadcrumb`, and `deploy`.

The client object has three constructor arguments that modify the way it will operate:
- `configuration` Accepts a `Configuration` object for the user to customize behavior of the Bugsnag notifier. The configuration options will be set in the most appropriate method for the framework being used, and parsed into this format.
- `resolver` Accepts an object that implements the `ResolverInterface`.  This object will be responsible for returning an object that implements the `RequestInterface`, which will need to populate a `Report` upon request.  By default if not given a resolver the client will create a `BasicResolver`.
- `guzzle` Accepts a `Guzzle` object provided by the guzzler library to use as an HTTP request object in the `HTTPClient` object.  By default the client will create one using the static `ENDPOINT` variable.  If it's necessary to change these defaults, the static `make_guzzle` may be used with a defined base url and options array, with the result being passed into this argument.

When utilising this library in a framework-specific way it is advised that the client object creation is handled in a way that makes the most sense for access to the notify functions and in order to extract the most relevant data, e.g. by using a different `resolver` with framework-specific functions, or wrapping the client in a service provider.

There is a provided static `make` method that creates the client object with suitable default `Configuration` and `Guzzle` arguments with the `api_key` and `endpoint` allowing the user to set their Bugsnag settings.

Another important aspect of the client object's construction is the registering of callbacks with a `pipeline` object.  If created with the `make` method the default callbacks will be added to the `pipeline` unless
the `defaults` argument is set to false.  These default callbacks will be responsible for populating the `report` object with information when necessary, and will should be customized to extract the most relevant information from the error and `resolver` objects used in the framework.

### The Handler Class
The handler class provides static functions for registering the Bugsnag unhandled error and exception handlers, as well as a shutdown handler.  This is separate to the client object as these handlers and methods of registering them will change across frameworks. 

The static registration functions take an instance of the client object to utilise as a source of application information and as a notifier when an unhandled error or exception occurs.

As this is an optional part of the library that must be initiated separately it should be replaced by an appropriate method of hooking into the error handlers or loggers of the relevant framework.  It must respond to these events by creating a `Report` object from the `client` and exception, and then it must pass it into the client object's `notify` function for it to be sent off to the Bugsnag notification server.

### The Report Object
The Report class is used to create readable information from a PHP exception or throwable which can later be used to populate an HTTP request to notify Bugsnag. It is accessible through three static methods:
- `fromPHPError` to create from a given decompiled PHP Error from the error handler
- `fromPHPThrowable` to create from a PHP Throwable object
- `fromNamedError` to create from name and message strings

These methods should be used to create a report object to ensure that the correct fields are populated for the later notification stages.  

### The Pipeline and Callbacks
Upon being passed to the `notify` function, the report object will also be populated with information provided by a series of callbacks created with the `client` object.  Registered with the `registerCallback` method, each callback will be passed the `report` in turn and can populate it with additional information if necessary.

The pipeline object itself is responsible for executing these callbacks as a series of closures until they have all been able to access the `report` object and modify its content.  The pipeline object is merely a method of utilising the callbacks, and does not need to be modified per framework.

The default callbacks, registered through the `client` object's `registerDefaultCallbacks` use the `Resolver` and its `Request` objects to extract data about the current environment in the server, where the error-causing request originated from, and any more metadata it can report.

There are two callbacks registered with the pipeline automatically by the client: `BreadcrumbData` which ensures that the recorded `Breadcrumb` objects are attached to the `report`, and `NotificationSkipper` which stops the notification process in the event that the notification should not be sent i.e. when missing an `api_key` or a non-releasing `releaseStage`.

It is recommended to create callback functions that can extract additional information from the framework to attach as metadata if necessary.  These callbacks are automatically wrapped in a `CallbackBridge` when registered to the pipeline which ensures they will automatically be called.

### The HTTPClient
Once the `report` object has been populated by the `pipeline` a callback is finally triggered in the `notify` function that will send the `report` to the HTTPClient.  This object exists on the `client` and is intiated with a `guzzle` client that it will use to call off to the configured endpoint.

This object will queue each `report` object it is given until `send` is called.  At this point it will iteratively create a single object containing all of the data from the `queue`, and ensure that the payload is correctly set up to be sent while remaining under the payload size limit.

Once the payload has been fully constructed it will be posted to the configured endpoint via the guzzle object.

The HTTPClient is also responsible for the `deploy` call.

### Breadcrumbs
Bugsnag tracks a series of actions manually dictated by the user to be sent to the Bugsnag notify endpoint along with an exception.  These actions are stored as breadcrumb objects, and are stored in the recorder object in the `client`.  The recorder acts like a circular array, storing up to 25 breadcrumb objects at a time before the oldest breadcrumbs get overwritten.  This limit is imposed to ensure the size of the payload sent to Bugsnag does not exceed the API limits

The breadcrumb data is attached to the `report` payload by the BreadcrumbData callback, which is initiated by the `client` object in its construction.

When the `notify` function is called, manually or automatically, a breadcrumb is logged with details of the error or exception being sent. 

# Other Bugsnag PHP Framework Libraries
This section covers the other available Bugsnag notifiers for PHP frameworks and how they are implemented and connected to the main Bugsnag-PHP libary and each other.

## [Bugsnag-PSR-Logger](https://github.com/bugsnag/bugsnag-psr-logger)
This library implements the [PSR-3 Logger Interface](http://www.php-fig.org/psr/psr-3/) specification to enable users to attach Bugsnag into a standardized logging system. It consists of three classes:
- `AbstractLogger` an abstract class which implements the PSR spec `LoggerInterface`, which requires a `log` method in its implementors
- `BugsnagLogger` a logger class which extends the above class.  This logger will record `debug` or `info` logs as `breadcrumb` objects, and will notify Bugsnag of any other log type
- `MultiLogger` again extends the `AbstractLogger`, but accepts and array of loggers in its construction, allowing other PSR compliant loggers to be used simultaneously with the `BugsnagLogger`

## [Bugsnag-Wordpress](https://github.com/bugsnag/bugsnag-wordpress)
This plugin for wordpress enables Bugsnag through the plugins menu of the wordpress site. It requires an older version of this library (~ 2.2) and so some of the methods and features will likely have been refactored for the newer versions.

## [Bugsnag-Symfony](https://github.com/bugsnag/bugsnag-symfony)
This library provides an extended version of the base Bugsnag-PHP library customized for the [Symfony PHP framework](symfony.com).

### Dependency Management
The libary is bundled for inclusion in the Symfony app framework through the `RegisterBundle` function used for adding Symfony extensions as laid out [here](https://symfony.com/doc/current/bundles.html).  It utilises the `DependencyInjection` folder to set up the extension through the `Configuration.php` file to define the necessary default configuration options, drawing the rest from the app's `config.yml`.  This configuration is then read in and processed by the `BugsnagExtension.php` file, and the arguments are set on the container.

To build the client when requested, the `Configuration.php` defines a factory for the framework to use,  `ClientFactory.php`.  This factory wraps the creation methods for the `client` object and ensures that the client is configured correctly to get information from the framework and respond to events.  The `ClientFactory` configuration can be found in the Bugsnag service definition in `Resources/services.yml`.

### Customizing the `Client` object
The configuration passed through to the `ClientFactory` will modify several of the `client` object's properties.  In additional to the [configuration options](https://docs.bugsnag.com/platforms/php/symfony/configuration-options/) for the user to setup their particular configuration, it also defines the `resolver` object which gathers information that populates the `report` objects used in the notify method.

The `ClientFactory` registers the default callbacks to the `pipeline` to extract data for the report, but also adds an additional callback specific to Symfony to extract a user identifier from the user specific `Token` object.

Once the `client` has been created it is returned to the Symfony instance as a service, allowing it to be accessed through the application with the 
appropriate Symfony service access methods.

### Listening for Events
The library does not utilise the basic PHP `Handler` object to listen to events, rather it connects an event listener `BugsnagListener.php` to the Symfony instance in the `services.yml` file, connecting specific events directly to a method via the `tags` descriptor as mentioned in the [official documentation](https://symfony.com/doc/current/event_dispatcher.html).

### Symfony Resolver and Request
As defined in `Configuration.php` the client will use the `SymfonyResolver` class as its default resolver. This resolver ensures that there is a Symfony specific `Request` object available before handing it off to a `SymfonyRequest` object.  This object implements all the methods from the `RequestInterface`, allowing the default callbacks to extract and append data to reports whenever a notification occurs.

## [Bugsnag-Laravel](https://github.com/bugsnag/bugsnag-laravel)
The Bugsnag-Laravel library again extends the Bugsnag-PHP library and customizes its operation for the [Laravel application framework](https://laravel.com/).

### Dependency Management
Laravel uses a very similar dependency management system to Symfony, wrapping classes in `ServiceProviders` that can then be called later through an `Alias` or a `Facade`. The `BugsnagServiceProvider` class implements `boot` and `register` functions as described in the [service provider](https://laravel.com/docs/5.4/providers) documentation.  The `boot` function intialises the configuration and options of the provider, while the `register` function returns a singleton accessible throught the application.

### Customizing the `Client` object
The `register` function mentioned above creates the `client` object with a base `configuration` and `guzzle`, as well as a `LaravelResolver` to handle retrieving data from created `LaravelRequest` objects in the `Resolver`-`Request` pattern.

It draws its configuration from the Laravel `config` object which the framework automatically populates from the `.env` file or a created `Bugsnag.php` configuration file.

The default callbacks are registered to the `pipeline` in the `setupCallbacks` function, along with customized callbacks to extract custom and user information from the framework to attach to the report.

### Listening for events
The Laravel notifier uses the [Bugsnag-PSR-Logger](https://github.com/bugsnag/bugsnag-psr-logger) in order to automatically receive error and exception events from the Laravel framework, which is the Laravel preferred method instead of directly registering an `error-handler`.

The notifier wraps the PSR logger in a pair of classes, the `LaravelLogger` and `MultiLogger` for singular and multi-logging setups respectively.  These are added to the framework by aliasing the frameworks PSR-logger interface and class to the `bugsnag.logger` class or `bugsnag.multi` classes depending on the users setup.  This must be done manually within the `AppServiceProvider`.

### Notifying of Deployments
While deployment notifications can be sent through the client object, Laravel library also provided a deploy command through the `DeployCommand` class. This must be registered through the `commands` array in the `Kernel.php` Laravel file.

## [Bugsnag-Silex](https://github.com/bugsnag/bugsnag-silex)
The Bugsnag-Silex library adds Silex-specific methods and data-gathering for the [Silex micro-framework](silex.symfony.com).

### Dependency Management
Being based on Symfony, Silex uses a similar service provider system to Laravel and Symfony, where the provider is passed to the app using the `register` function in the app's main startup file.  The provider is an implementation of the `ServiceProviderInterface` class, with a `register` function that adds `client` and `resolver` objects to the Silex container.

The provider is split into three classes, `AbstractServiceProvider` as a base class and `Silex1ServiceProvider` and `Silex2ServiceProvider` as extensions of this base.  The version specific provider classes operate in the same way except the `Silex1ServiceProvider` defines a required `boot` function that does nothing.

Both classes call into their base class method `makeClient` to produce the `client` being registered.

### Customizing the `Client` object
Silex configuration options are added in an environment-specific file in the `config` folder.  These options are then automatically pulled into the app container when the environment is started.  In the `makeClient` function this config is pulled in and used to setup a newly created client object, along with the earlier created `resolver`.

This `SilexResolver` is used to retrieve data for each report using the `SilexRequest` class in the previously mentioned `Resolver`-`Request` pattern.

Standard default callbacks are registered along with an additional callback to detect the user if one isn't already configured.

### Listening for events
The Silex framework requires manual registering of error and exception handlers, which requires the user add a `notifyException` call into an error handler registered to the app container's `error` function.

## [Bugsnag-Magento](https://github.com/bugsnag/bugsnag-magento)
The Bugsnag-Magento module enables Bugsnag functionality through the Magento admin panel.  It uses an older version of the Bugsnag-PHP library packaged with the module and so some of the methods and features will likely have been refactored by later versions.

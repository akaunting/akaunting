# Recurr [![Build Status](https://travis-ci.org/simshaun/recurr.png)](https://travis-ci.org/simshaun/recurr.png) [![Latest Stable Version](https://poser.pugx.org/simshaun/recurr/v/stable.svg)](https://packagist.org/packages/simshaun/recurr) [![Total Downloads](https://poser.pugx.org/simshaun/recurr/downloads.svg)](https://packagist.org/packages/simshaun/recurr) [![Latest Unstable Version](https://poser.pugx.org/simshaun/recurr/v/unstable.svg)](https://packagist.org/packages/simshaun/recurr) [![License](https://poser.pugx.org/simshaun/recurr/license.svg)](https://packagist.org/packages/simshaun/recurr)

Recurr is a PHP library for working with recurrence rules ([RRULE](https://tools.ietf.org/html/rfc5545)) and converting them in to DateTime objects.

Recurr was developed as a precursor for a calendar with recurring events, and is heavily inspired by [rrule.js](https://github.com/jkbr/rrule).

Installing Recurr
------------

The recommended way to install Recurr is through [Composer](http://getcomposer.org).

`composer require simshaun/recurr`

Using Recurr 
-----------

### Creating RRULE rule objects ###

You can create a new Rule object by passing the ([RRULE](https://tools.ietf.org/html/rfc5545)) string or an array with the rule parts, the start date, end date (optional) and timezone.

```php
$timezone    = 'America/New_York';
$startDate   = new \DateTime('2013-06-12 20:00:00', new \DateTimeZone($timezone));
$endDate     = new \DateTime('2013-06-14 20:00:00', new \DateTimeZone($timezone)); // Optional
$rule        = new \Recurr\Rule('FREQ=MONTHLY;COUNT=5', $startDate, $endDate, $timezone);
```

You can also use chained methods to build your rule programmatically and get the resulting RRULE.

```php
$rule = (new \Recurr\Rule)
    ->setStartDate($startDate)
    ->setTimezone($timezone)
    ->setFreq('DAILY')
    ->setByDay(['MO', 'TU'])
    ->setUntil(new \DateTime('2017-12-31'))
;

echo $rule->getString(); //FREQ=DAILY;UNTIL=20171231T000000;BYDAY=MO,TU
```

### RRULE to DateTime objects ###

```php
$transformer = new \Recurr\Transformer\ArrayTransformer();

print_r($transformer->transform($rule));
```

1. `$transformer->transform(...)` returns a `RecurrenceCollection` of `Recurrence` objects.
2. Each `Recurrence` has `getStart()` and `getEnd()` methods that return a `\DateTime` object.
3. If the transformed `Rule` lacks an end date, `getEnd()` will return a `\DateTime` object equal to that of `getStart()`.

> Note: The transformer has a "virtual" limit (default 732) on the number of objects it generates.
> This prevents the script from crashing on an infinitely recurring rule.
> You can change the virtual limit with an `ArrayTransformerConfig` object that you pass to `ArrayTransformer`.

### Transformation Constraints ###

Constraints are used by the ArrayTransformer to allow or prevent certain dates from being added to a `RecurrenceCollection`. Recurr provides the following constraints:

- `AfterConstraint(\DateTime $after, $inc = false)`
- `BeforeConstraint(\DateTime $before, $inc = false)`
- `BetweenConstraint(\DateTime $after, \DateTime $before, $inc = false)`

`$inc` defines what happens if `$after` or `$before` are themselves recurrences. If `$inc = true`, they will be included in the collection. For example,

```php
$startDate   = new \DateTime('2014-06-17 04:00:00');
$rule        = new \Recurr\Rule('FREQ=MONTHLY;COUNT=5', $startDate);
$transformer = new \Recurr\Transformer\ArrayTransformer();

$constraint = new \Recurr\Transformer\Constraint\BeforeConstraint(new \DateTime('2014-08-01 00:00:00'));
print_r($transformer->transform($rule, $constraint));
```

> Note: If building your own constraint, it is important to know that dates which do not meet the constraint's requirements do **not** count toward the transformer's virtual limit. If you manually set your constraint's `$stopsTransformer` property to `false`, the transformer *might* crash via an infinite loop. See the `BetweenConstraint` for an example on how to prevent that.

### Post-Transformation `RecurrenceCollection` Filters ###

`RecurrenceCollection` provides the following chainable helper methods to filter out recurrences:

- `startsBetween(\DateTime $after, \DateTime $before, $inc = false)`
- `startsBefore(\DateTime $before, $inc = false)`
- `startsAfter(\DateTime $after, $inc = false)`
- `endsBetween(\DateTime $after, \DateTime $before, $inc = false)`
- `endsBefore(\DateTime $before, $inc = false)`
- `endsAfter(\DateTime $after, $inc = false)`

`$inc` defines what happens if `$after` or `$before` are themselves recurrences. If `$inc = true`, they will be included in the filtered collection. For example,

    pseudo...
    2014-06-01 startsBetween(2014-06-01, 2014-06-20) // false
    2014-06-01 startsBetween(2014-06-01, 2014-06-20, true) // true

> Note: `RecurrenceCollection` extends the Doctrine project's [ArrayCollection](https://github.com/doctrine/collections/blob/master/lib/Doctrine/Common/Collections/ArrayCollection.php) class.

RRULE to Text
--------------------------

Recurr supports transforming some recurrence rules into human readable text.
This feature is still in beta and only supports yearly, monthly, weekly, and daily frequencies.

```php
$rule = new Rule('FREQ=YEARLY;INTERVAL=2;COUNT=3;', new \DateTime());

$textTransformer = new TextTransformer();
echo $textTransformer->transform($rule);
```

If you need more than English you can pass in a translator with one of the
supported locales *(see translations folder)*.

```php
$rule = new Rule('FREQ=YEARLY;INTERVAL=2;COUNT=3;', new \DateTime());

$textTransformer = new TextTransformer(
    new \Recurr\Transformer\Translator('de')
);
echo $textTransformer->transform($rule);
```

Warnings
---------------

- **Monthly recurring rules **
  By default, if your start date is on the 29th, 30th, or 31st, Recurr will skip following months that don't have at least that many days.
  *(e.g. Jan 31 + 1 month = March)* 

This behavior is configurable:

```php
$timezone    = 'America/New_York';
$startDate   = new \DateTime('2013-01-31 20:00:00', new \DateTimeZone($timezone));
$rule        = new \Recurr\Rule('FREQ=MONTHLY;COUNT=5', $startDate, null, $timezone);
$transformer = new \Recurr\Transformer\ArrayTransformer();

$transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
$transformerConfig->enableLastDayOfMonthFix();
$transformer->setConfig($transformerConfig);

print_r($transformer->transform($rule));
// 2013-01-31, 2013-02-28, 2013-03-31, 2013-04-30, 2013-05-31
```


Contribute
----------

Feel free to comment or make pull requests. Please include tests with PRs.


License
-------

Recurr is licensed under the MIT License. See the LICENSE file for details.

<?php

/*
 * Copyright 2013 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based on rrule.js
 * Copyright 2010, Jakub Roztocil and Lars Schoning
 * https://github.com/jkbr/rrule/blob/master/LICENCE
 *
 * Based on python-dateutil - Extensions to the standard Python datetime module.
 * Copyright (c) 2003-2011 - Gustavo Niemeyer <gustavo@niemeyer.net>
 * Copyright (c) 2012 - Tomi Pieviläinen <tomi.pievilainen@iki.fi>
 */

namespace Recurr;

use Recurr\Exception\InvalidArgument;
use Recurr\Exception\InvalidRRule;
use Recurr\Exception\InvalidWeekday;

/**
 * This class is responsible for providing a programmatic way of building,
 * parsing, and handling RRULEs.
 *
 * http://www.ietf.org/rfc/rfc2445.txt
 *
 * Information, not contained in the built/parsed RRULE, necessary to determine
 * the various recurrence instance start time and dates are derived from the
 * DTSTART property (default: \DateTime()).
 *
 * For example, "FREQ=YEARLY;BYMONTH=1" doesn't specify a specific day within
 * the month or a time. This information would be the same as what is specified
 * for DTSTART.
 *
 *
 * BYxxx rule parts modify the recurrence in some manner. BYxxx rule parts for
 * a period of time which is the same or greater than the frequency generally
 * reduce or limit the number of occurrences of the recurrence generated.
 *
 * For example, "FREQ=DAILY;BYMONTH=1" reduces the number of recurrence
 * instances from all days (if BYMONTH tag is not present) to all days in
 * January.
 *
 * BYxxx rule parts for a period of time less than the frequency generally
 * increase or expand the number of occurrences of the recurrence.
 *
 * For example, "FREQ=YEARLY;BYMONTH=1,2" increases the number of days within
 * the yearly recurrence set from 1 (if BYMONTH tag is not present) to 2.
 *
 * If multiple BYxxx rule parts are specified, then after evaluating the
 * specified FREQ and INTERVAL rule parts, the BYxxx rule parts are applied to
 * the current set of evaluated occurrences in the following order:
 *
 * BYMONTH, BYWEEKNO, BYYEARDAY, BYMONTHDAY, BYDAY, BYHOUR,
 * BYMINUTE, BYSECOND and BYSETPOS; then COUNT and UNTIL are evaluated.
 *
 * Here is an example of evaluating multiple BYxxx rule parts.
 *
 * FREQ=YEARLY;INTERVAL=2;BYMONTH=1;BYDAY=SU;BYHOUR=8,9;BYMINUTE=30
 *
 * First, the "INTERVAL=2" would be applied to "FREQ=YEARLY" to arrive at
 *   "every other year".
 * Then, "BYMONTH=1" would be applied to arrive at "every January, every
 *   other year".
 * Then, "BYDAY=SU" would be applied to arrive at "every Sunday in January,
 *   every other year".
 * Then, "BYHOUR=8,9" would be applied to arrive at "every Sunday in January
 *   at 8 AM and 9 AM, every other year".
 * Then, "BYMINUTE=30" would be applied to arrive at "every Sunday in January
 *   at 8:30 AM and 9:30 AM, every other year".
 * Then, lacking information from RRULE, the second is derived from DTSTART, to
 *   end up in "every Sunday in January at 8:30:00 AM and 9:30:00 AM, every
 *   other year". Similarly, if the BYMINUTE, BYHOUR, BYDAY, BYMONTHDAY or
 *   BYMONTH rule part were missing, the appropriate minute, hour, day or month
 *   would have been retrieved from the "DTSTART" property.
 *
 * Example: The following is a rule which specifies 10 meetings which occur
 * every other day:
 *
 * FREQ=DAILY;COUNT=10;INTERVAL=2
 *
 * @package Recurr
 * @author  Shaun Simmons <shaun@envysphere.com>
 */
class Rule
{
    const TZ_FIXED = 'fixed';
    const TZ_FLOAT = 'floating';

    public static $freqs = array(
        'YEARLY'   => 0,
        'MONTHLY'  => 1,
        'WEEKLY'   => 2,
        'DAILY'    => 3,
        'HOURLY'   => 4,
        'MINUTELY' => 5,
        'SECONDLY' => 6,
    );

    /** @var string */
    protected $timezone;

    /** @var \DateTimeInterface|null */
    protected $startDate;

    /** @var \DateTimeInterface|null */
    protected $endDate;

    /** @var bool */
    protected $isStartDateFromDtstart = false;

    /** @var string */
    protected $freq;

    /** @var int */
    protected $interval = 1;

    /** @var bool */
    protected $isExplicitInterval = false;

    /** @var \DateTimeInterface|null */
    protected $until;

    /** @var int|null */
    protected $count;

    /** @var array */
    protected $bySecond;

    /** @var array */
    protected $byMinute;

    /** @var array */
    protected $byHour;

    /** @var array */
    protected $byDay;

    /** @var array */
    protected $byMonthDay;

    /** @var array */
    protected $byYearDay;

    /** @var array */
    protected $byWeekNumber;

    /** @var array */
    protected $byMonth;

    /** @var string */
    protected $weekStart = 'MO';
    protected $weekStartDefined = false;

    /** @var array */
    protected $days = array(
        'MO' => 0,
        'TU' => 1,
        'WE' => 2,
        'TH' => 3,
        'FR' => 4,
        'SA' => 5,
        'SU' => 6
    );

    /** @var int[] */
    protected $bySetPosition;

    /** @var array */
    protected $rDates = array();

    /** @var array */
    protected $exDates = array();

    /**
     * Construct a new Rule.
     *
     * @param string                         $rrule RRULE string
     * @param string|\DateTimeInterface|null $startDate
     * @param string|\DateTimeInterface|null $endDate
     * @param string                         $timezone
     *
     * @throws InvalidRRule
     */
    public function __construct($rrule = null, $startDate = null, $endDate = null, $timezone = null)
    {
        if (empty($timezone)) {
            if ($startDate instanceof \DateTimeInterface) {
                $timezone = $startDate->getTimezone()->getName();
            } else {
                $timezone = date_default_timezone_get();
            }
        }
        $this->setTimezone($timezone);

        if ($startDate !== null && !$startDate instanceof \DateTimeInterface) {
            $startDate = new \DateTime($startDate, new \DateTimeZone($timezone));
        }

        $this->setStartDate($startDate);

        if ($endDate !== null && !$endDate instanceof \DateTimeInterface) {
            $endDate = new \DateTime($endDate, new \DateTimeZone($timezone));
        }

        $this->setEndDate($endDate);

        if (is_array($rrule)) {
            $this->loadFromArray($rrule);
        } else if (!empty($rrule)) {
            $this->loadFromString($rrule);
        }
    }

    /**
     * Create a Rule object based on a RRULE string.
     *
     * @param string                    $rrule RRULE string
     * @param string|\DateTimeInterface $startDate
     * @param \DateTimeInterface|null   $endDate
     * @param string                    $timezone
     *
     * @return Rule
     * @throws InvalidRRule
     */
    public static function createFromString($rrule, $startDate = null, $endDate = null, $timezone = null)
    {
        $rule = new static($rrule, $startDate, $endDate, $timezone);

        return $rule;
    }

    /**
     * Create a Rule object based on a RRULE array.
     *
     * @param array                     $rrule RRULE array
     * @param string|\DateTimeInterface $startDate
     * @param \DateTimeInterface|null   $endDate
     * @param string                    $timezone
     *
     * @return Rule
     * @throws InvalidRRule
     */
    public static function createFromArray($rrule, $startDate = null, $endDate = null, $timezone = null)
    {
        $rule = new static($rrule, $startDate, $endDate, $timezone);

        return $rule;
    }

    /**
     * Populate the object based on a RRULE string.
     *
     * @param string $rrule RRULE string
     *
     * @return Rule
     * @throws InvalidRRule
     */
    public function loadFromString($rrule)
    {
        $rrule  = strtoupper($rrule);
        $rrule  = trim($rrule, ';');
        $rrule  = trim($rrule, "\n");
        $rows   = explode("\n", $rrule);

        $parts = array();

        foreach ($rows as $rruleForRow) {
            $parts = array_merge($parts, $this->parseString($rruleForRow));
        }

        return $this->loadFromArray($parts);
    }

    /**
     * Parse string for parts
     *
     * @param string $rrule
     *
     * @return array
     *
     * @throws InvalidRRule
     */
    public function parseString($rrule)
    {
        if (strpos($rrule, 'DTSTART:') === 0) {
            $pieces = explode(':', $rrule);

            if (count($pieces) !== 2) {
                throw new InvalidRRule('DSTART is not valid');
            }

            return array('DTSTART' => $pieces[1]);
        }

        if (strpos($rrule, 'RRULE:') === 0) {
            $rrule = str_replace('RRULE:', '', $rrule);
        }

        $pieces = explode(';', $rrule);
        $parts  = array();

        if (!count($pieces)) {
            throw new InvalidRRule('RRULE is empty');
        }

        // Split each piece of the RRULE in to KEY=>VAL
        foreach ($pieces as $piece) {
            if (false === strpos($piece, '=')) {
                continue;
            }

            list($key, $val) = explode('=', $piece);
            $parts[$key] = $val;
        }

        return $parts;
    }

    /**
     * Populate the object based on a RRULE array.
     *
     * @param array
     *
     * @return Rule
     * @throws InvalidRRule
     */
    public function loadFromArray($parts)
    {
        // FREQ is required
        if (!isset($parts['FREQ'])) {
            throw new InvalidRRule('FREQ is required');
        } else {
            if (!in_array($parts['FREQ'], array_keys(self::$freqs))) {
                throw new InvalidRRule('FREQ is invalid');
            }

            $this->setFreq(self::$freqs[$parts['FREQ']]);
        }

        // DTSTART
        if (isset($parts['DTSTART'])) {
            $this->isStartDateFromDtstart = true;
            $date = new \DateTime($parts['DTSTART']);
            $date = $date->setTimezone(new \DateTimeZone($this->getTimezone()));
            $this->setStartDate($date);
        }

        // DTEND
        if (isset($parts['DTEND'])) {
            $date = new \DateTime($parts['DTEND']);
            $date = $date->setTimezone(new \DateTimeZone($this->getTimezone()));
            $this->setEndDate($date);
        }

        // UNTIL or COUNT
        if (isset($parts['UNTIL']) && isset($parts['COUNT'])) {
            throw new InvalidRRule('UNTIL and COUNT must not exist together in the same RRULE');
        } elseif (isset($parts['UNTIL'])) {
            $date = new \DateTime($parts['UNTIL']);
            $date = $date->setTimezone(new \DateTimeZone($this->getTimezone()));
            $this->setUntil($date);
        } elseif (isset($parts['COUNT'])) {
            $this->setCount($parts['COUNT']);
        }

        // INTERVAL
        if (isset($parts['INTERVAL'])) {
            $this->setInterval($parts['INTERVAL']);
        }

        // BYSECOND
        if (isset($parts['BYSECOND'])) {
            $this->setBySecond(explode(',', $parts['BYSECOND']));
        }

        // BYMINUTE
        if (isset($parts['BYMINUTE'])) {
            $this->setByMinute(explode(',', $parts['BYMINUTE']));
        }

        // BYHOUR
        if (isset($parts['BYHOUR'])) {
            $this->setByHour(explode(',', $parts['BYHOUR']));
        }

        // BYDAY
        if (isset($parts['BYDAY'])) {
            $this->setByDay(explode(',', $parts['BYDAY']));
        }

        // BYMONTHDAY
        if (isset($parts['BYMONTHDAY'])) {
            $this->setByMonthDay(explode(',', $parts['BYMONTHDAY']));
        }

        // BYYEARDAY
        if (isset($parts['BYYEARDAY'])) {
            $this->setByYearDay(explode(',', $parts['BYYEARDAY']));
        }

        // BYWEEKNO
        if (isset($parts['BYWEEKNO'])) {
            $this->setByWeekNumber(explode(',', $parts['BYWEEKNO']));
        }

        // BYMONTH
        if (isset($parts['BYMONTH'])) {
            $this->setByMonth(explode(',', $parts['BYMONTH']));
        }

        // BYSETPOS
        if (isset($parts['BYSETPOS'])) {
            $this->setBySetPosition(explode(',', $parts['BYSETPOS']));
        }

        // WKST
        if (isset($parts['WKST'])) {
            $this->setWeekStart($parts['WKST']);
        }

        // RDATE
        if (isset($parts['RDATE'])) {
            $this->setRDates(explode(',', $parts['RDATE']));
        }

        // EXDATE
        if (isset($parts['EXDATE'])) {
            $this->setExDates(explode(',', $parts['EXDATE']));
        }

        return $this;
    }

    /**
     * Get the RRULE as a string
     *
     * @param string $timezoneType
     *
     * @return string
     */
    public function getString($timezoneType=self::TZ_FLOAT)
    {
        $format = 'Ymd\THis';

        $parts = array();

        // FREQ
        $parts[] = 'FREQ='.$this->getFreqAsText();

        // UNTIL or COUNT
        $until = $this->getUntil();
        $count = $this->getCount();
        if (!empty($until)) {
            if ($timezoneType === self::TZ_FIXED) {
                $u = clone $until;
                $u = $u->setTimezone(new \DateTimeZone('UTC'));
                $parts[] = 'UNTIL='.$u->format($format.'\Z');
            } else {
                $parts[] = 'UNTIL='.$until->format($format);
            }
        } elseif (!empty($count)) {
            $parts[] = 'COUNT='.$count;
        }

        // DTSTART
        if ($this->isStartDateFromDtstart) {
            if ($timezoneType === self::TZ_FIXED) {
                $d = $this->getStartDate();
                $tzid = $d->getTimezone()->getName();
                $date = $d->format($format);
                $parts[] = "DTSTART;TZID=$tzid:$date";
            } else {
                $parts[] = 'DTSTART='.$this->getStartDate()->format($format);
            }
        }

        // DTEND
        if ($this->endDate instanceof \DateTime) {
            if ($timezoneType === self::TZ_FIXED) {
                $d = $this->getEndDate();
                $tzid = $d->getTimezone()->getName();
                $date = $d->format($format);

                $parts[] = "DTEND;TZID=$tzid:$date";
            } else {
                $parts[] = 'DTEND='.$this->getEndDate()->format($format);
            }
        }

        // INTERVAL
        $interval = $this->getInterval();
        if ($this->isExplicitInterval && !empty($interval)) {
            $parts[] = 'INTERVAL='.$interval;
        }

        // BYSECOND
        $bySecond = $this->getBySecond();
        if (!empty($bySecond)) {
            $parts[] = 'BYSECOND='.implode(',', $bySecond);
        }

        // BYMINUTE
        $byMinute = $this->getByMinute();
        if (!empty($byMinute)) {
            $parts[] = 'BYMINUTE='.implode(',', $byMinute);
        }

        // BYHOUR
        $byHour = $this->getByHour();
        if (!empty($byHour)) {
            $parts[] = 'BYHOUR='.implode(',', $byHour);
        }

        // BYDAY
        $byDay = $this->getByDay();
        if (!empty($byDay)) {
            $parts[] = 'BYDAY='.implode(',', $byDay);
        }

        // BYMONTHDAY
        $byMonthDay = $this->getByMonthDay();
        if (!empty($byMonthDay)) {
            $parts[] = 'BYMONTHDAY='.implode(',', $byMonthDay);
        }

        // BYYEARDAY
        $byYearDay = $this->getByYearDay();
        if (!empty($byYearDay)) {
            $parts[] = 'BYYEARDAY='.implode(',', $byYearDay);
        }

        // BYWEEKNO
        $byWeekNumber = $this->getByWeekNumber();
        if (!empty($byWeekNumber)) {
            $parts[] = 'BYWEEKNO='.implode(',', $byWeekNumber);
        }

        // BYMONTH
        $byMonth = $this->getByMonth();
        if (!empty($byMonth)) {
            $parts[] = 'BYMONTH='.implode(',', $byMonth);
        }

        // BYSETPOS
        $bySetPosition = $this->getBySetPosition();
        if (!empty($bySetPosition)) {
            $parts[] = 'BYSETPOS='.implode(',', $bySetPosition);
        }

        // WKST
        $weekStart = $this->getWeekStart();
        if ($this->weekStartDefined && !empty($weekStart)) {
            $parts[] = 'WKST='.$weekStart;
        }

        // RDATE
        $rDates = $this->getRDates();
        if (!empty($rDates)) {
            foreach ($rDates as $key => $inclusion) {
                $format = 'Ymd';
                if ($inclusion->hasTime) {
                    $format .= '\THis';
                    if ($inclusion->isUtcExplicit) {
                        $format .= '\Z';
                    }
                }
                $rDates[$key] = $inclusion->date->format($format);
            }
            $parts[] = 'RDATE='.implode(',', $rDates);
        }

        // EXDATE
        $exDates = $this->getExDates();
        if (!empty($exDates)) {
            foreach ($exDates as $key => $exclusion) {
                $format = 'Ymd';
                if ($exclusion->hasTime) {
                    $format .= '\THis';
                    if ($exclusion->isUtcExplicit) {
                        $format .= '\Z';
                    }
                }
                $exDates[$key] = $exclusion->date->format($format);
            }
            $parts[] = 'EXDATE='.implode(',', $exDates);
        }

        return implode(';', $parts);
    }

    /**
     * @param string $timezone
     *
     * @see http://www.php.net/manual/en/timezones.php
     * @return $this
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone to use for \DateTimeInterface objects that are UTC.
     *
     * @return null|string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * This date specifies the first instance in the recurrence set.
     *
     * @param \DateTimeInterface|null $startDate       Date of the first instance in the recurrence
     * @param bool|null               $includeInString If true, include as DTSTART when calling getString()
     *
     * @return $this
     */
    public function setStartDate($startDate, $includeInString = null)
    {
        $this->startDate = $startDate;

        if ($includeInString !== null) {
            $this->isStartDateFromDtstart = (bool) $includeInString;
        }

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * This date specifies the last possible instance in the recurrence set.
     *
     * @param \DateTimeInterface|null $endDate Date of the last possible instance in the recurrence
     *
     * @return $this
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Identifies the type of recurrence rule.
     *
     * May be one of:
     *  - Frequency::SECONDLY to specify repeating events based on an
     *    interval of a second or more.
     *  - Frequency::MINUTELY to specify repeating events based on an
     *    interval of a minute or more.
     *  - Frequency::HOURLY to specify repeating events based on an
     *    interval of an hour or more.
     *  - Frequency::DAILY to specify repeating events based on an
     *    interval of a day or more.
     *  - Frequency::WEEKLY to specify repeating events based on an
     *    interval of a week or more.
     *  - Frequency::MONTHLY to specify repeating events based on an
     *    interval of a month or more.
     *  - Frequency::YEAR to specify repeating events based on an
     *    interval of a year or more.
     *
     * @param string|int $freq Frequency of recurrence.
     *
     * @return $this
     * @throws Exception\InvalidArgument
     */
    public function setFreq($freq)
    {
        if (is_string($freq)) {
            if (!array_key_exists($freq, self::$freqs)) {
                throw new InvalidArgument('Frequency must comply with RFC 2445.');
            } else {
                $freq = self::$freqs[$freq];
            }
        }

        if (is_int($freq) && ($freq < 0 || $freq > 6)) {
            throw new InvalidArgument('Frequency integer must be between 0 and 6 Use the class constants.');
        }

        $this->freq = $freq;

        return $this;
    }

    /**
     * Get the type of recurrence rule (as integer).
     *
     * @return int
     */
    public function getFreq()
    {
        return $this->freq;
    }

    /**
     * Get the type of recurrence rule (as text).
     *
     * @return string
     */
    public function getFreqAsText()
    {
        return array_search($this->getFreq(), self::$freqs);
    }

    /**
     * The interval represents how often the recurrence rule repeats.
     *
     * The default value is "1", meaning every second for a SECONDLY rule,
     * or every minute for a MINUTELY rule, every hour for an HOURLY rule,
     * every day for a DAILY rule, every week for a WEEKLY rule, every month
     * for a MONTHLY rule and every year for a YEARLY rule.
     *
     * @param int $interval Positive integer that represents how often the
     *                      recurrence rule repeats.
     *
     * @return $this
     * @throws Exception\InvalidArgument
     */
    public function setInterval($interval)
    {
        $interval = (int) $interval;

        if ($interval < 1) {
            throw new InvalidArgument('Interval must be a positive integer');
        }

        $this->interval           = $interval;
        $this->isExplicitInterval = true;

        return $this;
    }

    /**
     * Get the interval that represents how often the recurrence rule repeats.
     *
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Define a \DateTimeInterface value which bounds the recurrence rule in an
     * inclusive manner. If the value specified is synchronized with the
     * specified recurrence, this DateTime becomes the last instance of the
     * recurrence. If not present, and a COUNT is also not present, the RRULE
     * is considered to repeat forever.
     *
     * Either UNTIL or COUNT may be specified, but UNTIL and COUNT MUST NOT
     * both be specified.
     *
     * @param \DateTimeInterface $until The upper bound of the recurrence.
     *
     * @return $this
     */
    public function setUntil(\DateTimeInterface $until)
    {
        $this->until = $until;
        $this->count = null;

        return $this;
    }

    /**
     * Get the \DateTimeInterface that the recurrence lasts until.
     *
     * @return \DateTimeInterface|null
     */
    public function getUntil()
    {
        $date = $this->until;

        if ($date instanceof \DateTime
            && $date->getTimezone()->getName() == 'UTC'
            && $this->getTimezone() != 'UTC'
        ) {
            $timestamp = $date->getTimestamp();
            $date = $date->setTimezone(new \DateTimeZone($this->getTimezone()));
            $date = $date->setTimestamp($timestamp);
        }

        return $date;
    }

    /**
     * The count defines the number of occurrences at which to range-bound the
     * recurrence. The DTSTART counts as the first occurrence.
     *
     * Either COUNT or UNTIL may be specified, but COUNT and UNTIL MUST NOT
     * both be specified.
     *
     * @param int $count Number of occurrences
     *
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = (int) $count;
        $this->until = null;

        return $this;
    }

    /**
     * Get the number of occurrences at which the recurrence is range-bound.
     *
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * This rule specifies an array of seconds within a minute.
     *
     * Valid values are 0 to 59.
     *
     * @param array $bySecond Array of seconds within a minute
     *
     * @return $this
     */
    public function setBySecond(array $bySecond)
    {
        $this->bySecond = $bySecond;

        return $this;
    }

    /**
     * Get an array of seconds within a minute.
     *
     * @return array
     */
    public function getBySecond()
    {
        return $this->bySecond;
    }

    /**
     * This rule specifies an array of minutes within an hour.
     *
     * Valid values are 0 to 59.
     *
     * @param array $byMinute Array of minutes within an hour
     *
     * @return $this
     */
    public function setByMinute(array $byMinute)
    {
        $this->byMinute = $byMinute;

        return $this;
    }

    /**
     * Get an array of minutes within an hour.
     *
     * @return array
     */
    public function getByMinute()
    {
        return $this->byMinute;
    }

    /**
     * This rule specifies an array of hours of the day.
     *
     * Valid values are 0 to 23.
     *
     * @param array $byHour Array of hours of the day
     *
     * @return $this
     */
    public function setByHour(array $byHour)
    {
        $this->byHour = $byHour;

        return $this;
    }

    /**
     * Get an array of hours of the day.
     *
     * @return array
     */
    public function getByHour()
    {
        return $this->byHour;
    }

    /**
     * This rule specifies an array of days of the week;
     *
     * MO indicates Monday; TU indicates Tuesday; WE indicates Wednesday;
     * TH indicates Thursday; FR indicates Friday; SA indicates Saturday;
     * SU indicates Sunday.
     *
     * Each BYDAY value can also be preceded by a positive (+n) or negative
     * (-n) integer. If present, this indicates the nth occurrence of the
     * specific day within the MONTHLY or YEARLY RRULE. For example, within
     * a MONTHLY rule, +1MO (or simply 1MO) represents the first Monday
     * within the month, whereas -1MO represents the last Monday of the
     * month. If an integer modifier is not present, it means all days of
     * this type within the specified frequency. For example, within a
     * MONTHLY rule, MO represents all Mondays within the month.
     *
     * -------------------------------------------
     * DO NOT MIX DAYS AND DAYS WITH MODIFIERS.
     * This is not supported.
     * -------------------------------------------
     *
     * @param array $byDay Array of days of the week
     *
     * @return $this
     * @throws InvalidRRule
     */
    public function setByDay(array $byDay)
    {
        if ($this->getFreq() > static::$freqs['MONTHLY'] && preg_match('/\d/', implode(',', $byDay))) {
            throw new InvalidRRule('BYDAY only supports MONTHLY and YEARLY frequencies');
        }
        if (count($byDay) === 0 || $byDay === array('')) {
            throw new InvalidRRule('BYDAY must be set to at least one day');
        }

        $this->byDay = $byDay;

        return $this;
    }

    /**
     * Get an array of days of the week (SU, MO, TU, ..)
     *
     * @return array
     */
    public function getByDay()
    {
        return $this->byDay;
    }

    /**
     * Get an array of Weekdays
     *
     * @return array of Weekdays
     * @throws InvalidWeekday
     */
    public function getByDayTransformedToWeekdays()
    {
        $byDay = $this->getByDay();

        if (null === $byDay || !count($byDay)) {
            return array();
        }

        foreach ($byDay as $idx => $day) {
            if (strlen($day) === 2) {
                $byDay[$idx] = new Weekday($day, null);
            } else {
                preg_match('/^([+-]?[0-9]+)([A-Z]{2})$/', $day, $dayParts);
                $byDay[$idx] = new Weekday($dayParts[2], $dayParts[1]);
            }
        }

        return $byDay;
    }

    /**
     * This rule specifies an array of days of the month.
     * Valid values are 1 to 31 or -31 to -1.
     *
     * For example, -10 represents the tenth to the last day of the month.
     *
     * @param array $byMonthDay Array of days of the month from -31 to 31
     *
     * @return $this
     */
    public function setByMonthDay(array $byMonthDay)
    {
        $this->byMonthDay = $byMonthDay;

        return $this;
    }

    /**
     * Get an array of days of the month.
     *
     * @return array
     */
    public function getByMonthDay()
    {
        return $this->byMonthDay;
    }

    /**
     * This rule specifies an array of days of the year.
     * Valid values are 1 to 366 or -366 to -1.
     *
     * For example, -1 represents the last day of the year (December 31st) and
     * -306 represents the 306th to the last day of the year (March 1st).
     *
     * @param array $byYearDay Array of days of the year from -1 to 306
     *
     * @return $this
     */
    public function setByYearDay(array $byYearDay)
    {
        $this->byYearDay = $byYearDay;

        return $this;
    }

    /**
     * Get an array of days of the year.
     *
     * @return array
     */
    public function getByYearDay()
    {
        return $this->byYearDay;
    }

    /**
     * This rule specifies an array of ordinals specifying weeks of the year.
     * Valid values are 1 to 53 or -53 to -1.
     *
     * This corresponds to weeks according to week numbering as defined in
     * [ISO 8601]. A week is defined as a seven day period, starting on the day
     * of the week defined to be the week start (see setWeekStart). Week number
     * one of the calendar year is the first week which contains at least four
     * days in that calendar year. This rule is only valid for YEARLY rules.
     *
     * For example, 3 represents the third week of the year.
     *
     * Note: Assuming a Monday week start, week 53 can only occur when
     *  Thursday is January 1 or if it is a leap year and Wednesday is January 1.
     *
     * @param array $byWeekNumber Array of ordinals specifying weeks of the year.
     *
     * @return $this
     */
    public function setByWeekNumber(array $byWeekNumber)
    {
        $this->byWeekNumber = $byWeekNumber;

        return $this;
    }

    /**
     * Get an array of ordinals specifying weeks of the year.
     *
     * @return array
     */
    public function getByWeekNumber()
    {
        return $this->byWeekNumber;
    }

    /**
     * This rule specifies an array of months of the year.
     *
     * Valid values are 1 to 12.
     *
     * @param array $byMonth Array of months of the year from 1 to 12
     *
     * @return $this
     */
    public function setByMonth(array $byMonth)
    {
        $this->byMonth = $byMonth;

        return $this;
    }

    /**
     * Get an array of months of the year.
     *
     * @return array
     */
    public function getByMonth()
    {
        return $this->byMonth;
    }

    public function hasByMonth()
    {
        $val = $this->getByMonth();

        return ! empty($val);
    }

    /**
     * This rule specifies the day on which the workweek starts.
     *
     * Valid values are MO, TU, WE, TH, FR, SA and SU.
     *
     * This is significant when a WEEKLY RRULE has an interval greater than 1,
     * and a BYDAY rule part is specified.
     *
     * This is also significant when in a YEARLY RRULE when a BYWEEKNO rule
     * is specified. The default value is MO.
     *
     * @param string $weekStart The day on which the workweek starts.
     *
     * @return $this
     * @throws Exception\InvalidArgument
     */
    public function setWeekStart($weekStart)
    {
        $weekStart = strtoupper($weekStart);

        if (!in_array($weekStart, array('MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU'))) {
            throw new InvalidArgument('Week Start must be one of MO, TU, WE, TH, FR, SA, SU');
        }

        $this->weekStart        = $weekStart;
        $this->weekStartDefined = true;

        return $this;
    }

    /**
     * Get the day on which the workweek starts.
     *
     * @return string
     */
    public function getWeekStart()
    {
        return $this->weekStart;
    }

    /**
     * Get the day on which the workweek starts, as an integer from 0-6,
     * 0 being Monday and 6 being Sunday.
     *
     * @return int
     */
    public function getWeekStartAsNum()
    {
        $weekStart = $this->getWeekStart();

        return $this->days[$weekStart];
    }

    /**
     * This rule specifies an array of values which corresponds to the nth
     * occurrence within the set of events specified by the rule. Valid values
     * are 1 to 366 or -366 to -1. It MUST only be used in conjunction with
     * another BYxxx rule part.
     *
     * For example "the last work day of the month" could be represented as:
     *   RRULE:FREQ=MONTHLY;BYDAY=MO,TU,WE,TH,FR;BYSETPOS=-1
     *
     * Each BYSETPOS value can include a positive or negative integer.
     * If present, this indicates the nth occurrence of the specific occurrence
     * within the set of events specified by the rule.
     *
     * @param array $bySetPosition Array of values which corresponds to the nth
     *                             occurrence within the set of events specified by the rule.
     *
     * @return $this
     */
    public function setBySetPosition($bySetPosition)
    {
        $this->bySetPosition = $bySetPosition;

        return $this;
    }

    /**
     * Get the array of values which corresponds to the nth occurrence within
     * the set of events specified by the rule.
     *
     * @return array
     */
    public function getBySetPosition()
    {
        return $this->bySetPosition;
    }

    /**
     * This rule specifies an array of dates that will be
     * included in a recurrence set.
     *
     * @param string[]|DateInclusion[] $rDates Array of dates that will be
     *                                         included in the recurrence set.
     *
     * @return $this
     */
    public function setRDates(array $rDates)
    {
        $timezone = new \DateTimeZone($this->getTimezone());

        foreach ($rDates as $key => $val) {
            if ($val instanceof DateInclusion) {
                $val->date = $this->convertZtoUtc($val->date);
            } else {
                $date          = new \DateTime($val, $timezone);
                $rDates[$key] = new DateInclusion(
                    $this->convertZtoUtc($date),
                    strpos($val, 'T') !== false,
                    strpos($val, 'Z') !== false
                );
            }
        }

        $this->rDates = $rDates;

        return $this;
    }

    /**
     * Get the array of dates that will be included in a recurrence set.
     *
     * @return DateInclusion[]
     */
    public function getRDates()
    {
        return $this->rDates;
    }

    /**
     * This rule specifies an array of exception dates that will not be
     * included in a recurrence set.
     *
     * @param string[]|DateExclusion[] $exDates Array of dates that will not be
     *                                          included in the recurrence set.
     *
     * @return $this
     */
    public function setExDates(array $exDates)
    {
        $timezone = new \DateTimeZone($this->getTimezone());

        foreach ($exDates as $key => $val) {
            if ($val instanceof DateExclusion) {
                $val->date = $this->convertZtoUtc($val->date);
            } else {
                $date          = new \DateTime($val, $timezone);
                $exDates[$key] = new DateExclusion(
                    $this->convertZtoUtc($date),
                    strpos($val, 'T') !== false,
                    strpos($val, 'Z') !== false
                );
            }
        }

        $this->exDates = $exDates;

        return $this;
    }

    /**
     * DateTime::setTimezone fails if the timezone does not have an ID.
     * "Z" is the same as "UTC", but "Z" does not have an ID.
     *
     * This is necessary for exclusion dates to be handled properly.
     *
     * @param \DateTimeInterface $date
     *
     * @return \DateTimeInterface
     */
    private function convertZtoUtc(\DateTimeInterface $date)
    {
        if ($date->getTimezone()->getName() !== 'Z') {
            return $date;
        }

        return $date->setTimezone(new \DateTimeZone('UTC'));
    }

    /**
     * Get the array of dates that will not be included in a recurrence set.
     *
     * @return DateExclusion[]
     */
    public function getExDates()
    {
        return $this->exDates;
    }

    /**
     * @return bool
     */
    public function repeatsIndefinitely()
    {
        return !$this->getCount() && !$this->getUntil() && !$this->getEndDate();
    }
}

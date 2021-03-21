<?php

/*
 * Copyright 2014 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based on:
 * rrule.js - Library for working with recurrence rules for calendar dates.
 * Copyright 2010, Jakub Roztocil and Lars Schoning
 * https://github.com/jkbr/rrule/blob/master/LICENCE
 */

namespace Recurr;

class Frequency {
    const YEARLY   = 0;
    const MONTHLY  = 1;
    const WEEKLY   = 2;
    const DAILY    = 3;
    const HOURLY   = 4;
    const MINUTELY = 5;
    const SECONDLY = 6;
}
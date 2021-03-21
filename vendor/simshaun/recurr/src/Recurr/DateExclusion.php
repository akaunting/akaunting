<?php

/*
 * Copyright 2014 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Recurr;

/**
 * Class DateExclusion is a container for a single \DateTimeInterface.
 *
 * The purpose of this class is to hold a flag that specifies whether
 * or not the \DateTimeInterface was created from a DATE only, or with a
 * DATETIME.
 *
 * It also tracks whether or not the exclusion is explicitly set to UTC.
 *
 * @package Recurr
 * @author  Shaun Simmons <shaun@envysphere.com>
 */
class DateExclusion
{
    /** @var \DateTimeInterface */
    public $date;

    /** @var bool Day of year */
    public $hasTime;

    /** @var bool */
    public $isUtcExplicit;

    /**
     * Constructor
     *
     * @param \DateTimeInterface $date
     * @param bool               $hasTime
     * @param bool               $isUtcExplicit
     */
    public function __construct(\DateTimeInterface $date, $hasTime = true, $isUtcExplicit = false)
    {
        $this->date          = $date;
        $this->hasTime       = $hasTime;
        $this->isUtcExplicit = $isUtcExplicit;
    }
}

<?php

/*
 * Copyright 2014 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Recurr;

/**
 * Class Recurrence is responsible for storing the start and end \DateTime of
 * a specific recurrence in a RRULE.
 *
 * @package Recurr
 * @author  Shaun Simmons <shaun@envysphere.com>
 */
class Recurrence
{
    /** @var \DateTimeInterface */
    protected $start;

    /** @var \DateTimeInterface */
    protected $end;

    /** @var int */
    protected $index;

    public function __construct(\DateTimeInterface $start = null, \DateTimeInterface $end = null, $index = 0)
    {
        if ($start instanceof \DateTimeInterface) {
            $this->setStart($start);
        }

        if ($end instanceof \DateTimeInterface) {
            $this->setEnd($end);
        }

        $this->index = $index;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }
}

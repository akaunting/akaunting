<?php

/*
 * Copyright 2014 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Recurr;

use \Doctrine\Common\Collections\ArrayCollection as BaseCollection;

/**
 * @package Recurr
 * @author  Shaun Simmons <shaun@envysphere.com>
 */
class RecurrenceCollection extends BaseCollection
{
    /**
     * @param \DateTimeInterface $after
     * @param \DateTimeInterface $before
     * @param bool      $inc Include $after or $before if they happen to be a recurrence.
     *
     * @return RecurrenceCollection
     */
    public function startsBetween(\DateTimeInterface $after, \DateTimeInterface $before, $inc = false)
    {
        return $this->filter(
            function ($recurrence) use ($after, $before, $inc) {
                /** @var $recurrence Recurrence */
                $start = $recurrence->getStart();

                if ($inc) {
                    return $start >= $after && $start <= $before;
                }

                return $start > $after && $start < $before;
            }
        );
    }

    /**
     * @param \DateTimeInterface $before
     * @param bool               $inc Include $before if it is a recurrence.
     *
     * @return RecurrenceCollection
     */
    public function startsBefore(\DateTimeInterface $before, $inc = false)
    {
        return $this->filter(
            function ($recurrence) use ($before, $inc) {
                /** @var $recurrence Recurrence */
                $start = $recurrence->getStart();

                if ($inc) {
                    return $start <= $before;
                }

                return $start < $before;
            }
        );
    }

    /**
     * @param \DateTimeInterface $after
     * @param bool               $inc Include $after if it a recurrence.
     *
     * @return RecurrenceCollection
     */
    public function startsAfter(\DateTimeInterface $after, $inc = false)
    {
        return $this->filter(
            function ($recurrence) use ($after, $inc) {
                /** @var $recurrence Recurrence */
                $start = $recurrence->getStart();

                if ($inc) {
                    return $start >= $after;
                }

                return $start > $after;
            }
        );
    }

    /**
     * @param \DateTimeInterface $after
     * @param \DateTimeInterface $before
     * @param bool               $inc Include $after or $before if they happen to be a recurrence.
     *
     * @return RecurrenceCollection
     */
    public function endsBetween(\DateTimeInterface $after, \DateTimeInterface $before, $inc = false)
    {
        return $this->filter(
            function ($recurrence) use ($after, $before, $inc) {
                /** @var $recurrence Recurrence */
                $end = $recurrence->getEnd();

                if ($inc) {
                    return $end >= $after && $end <= $before;
                }

                return $end > $after && $end < $before;
            }
        );
    }

    /**
     * @param \DateTimeInterface $before
     * @param bool               $inc Include $before if it is a recurrence.
     *
     * @return RecurrenceCollection
     */
    public function endsBefore(\DateTimeInterface $before, $inc = false)
    {
        return $this->filter(
            function ($recurrence) use ($before, $inc) {
                /** @var $recurrence Recurrence */
                $end = $recurrence->getEnd();

                if ($inc) {
                    return $end <= $before;
                }

                return $end < $before;
            }
        );
    }

    /**
     * @param \DateTimeInterface $after
     * @param bool               $inc Include $after if it a recurrence.
     *
     * @return RecurrenceCollection
     */
    public function endsAfter(\DateTimeInterface $after, $inc = false)
    {
        return $this->filter(
            function ($recurrence) use ($after, $inc) {
                /** @var $recurrence Recurrence */
                $end = $recurrence->getEnd();

                if ($inc) {
                    return $end >= $after;
                }

                return $end > $after;
            }
        );
    }
}

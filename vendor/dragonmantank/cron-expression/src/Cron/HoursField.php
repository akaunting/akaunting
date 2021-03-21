<?php

declare(strict_types=1);

namespace Cron;

use DateTimeInterface;
use DateTimeZone;

/**
 * Hours field.  Allows: * , / -.
 */
class HoursField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $rangeStart = 0;

    /**
     * {@inheritdoc}
     */
    protected $rangeEnd = 23;

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy(DateTimeInterface $date, $value): bool
    {
        return $this->isSatisfied((int) $date->format('H'), $value);
    }

    /**
     * {@inheritdoc}
     *
     * @param \DateTime|\DateTimeImmutable $date
     * @param string|null                  $parts
     */
    public function increment(DateTimeInterface &$date, $invert = false, $parts = null): FieldInterface
    {
        // Change timezone to UTC temporarily. This will
        // allow us to go back or forwards and hour even
        // if DST will be changed between the hours.
        if (null === $parts || '*' === $parts) {
            $timezone = $date->getTimezone();
            $date = $date->setTimezone(new DateTimeZone('UTC'));
            $date = $date->modify(($invert ? '-' : '+') . '1 hour');
            $date = $date->setTimezone($timezone);

            $date = $date->setTime((int)$date->format('H'), $invert ? 59 : 0);
            return $this;
        }

        $parts = false !== strpos($parts, ',') ? explode(',', $parts) : [$parts];
        $hours = [];
        foreach ($parts as $part) {
            $hours = array_merge($hours, $this->getRangeForExpression($part, 23));
        }

        $current_hour = $date->format('H');
        $position = $invert ? \count($hours) - 1 : 0;
        $countHours = \count($hours);
        if ($countHours > 1) {
            for ($i = 0; $i < $countHours - 1; ++$i) {
                if ((!$invert && $current_hour >= $hours[$i] && $current_hour < $hours[$i + 1]) ||
                    ($invert && $current_hour > $hours[$i] && $current_hour <= $hours[$i + 1])) {
                    $position = $invert ? $i : $i + 1;

                    break;
                }
            }
        }

        $hour = (int) $hours[$position];
        if ((!$invert && (int) $date->format('H') >= $hour) || ($invert && (int) $date->format('H') <= $hour)) {
            $date = $date->modify(($invert ? '-' : '+') . '1 day');
            $date = $date->setTime($invert ? 23 : 0, $invert ? 59 : 0);
        } else {
            $date = $date->setTime($hour, $invert ? 59 : 0);
        }

        return $this;
    }
}

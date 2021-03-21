<?php

declare(strict_types=1);

namespace Cron;

use DateTimeInterface;

/**
 * Minutes field.  Allows: * , / -.
 */
class MinutesField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $rangeStart = 0;

    /**
     * {@inheritdoc}
     */
    protected $rangeEnd = 59;

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy(DateTimeInterface $date, $value):bool
    {
        if ($value == '?') {
            return true;
        }

        return $this->isSatisfied((int)$date->format('i'), $value);
    }

    /**
     * {@inheritdoc}
     * {@inheritDoc}
     *
     * @param \DateTime|\DateTimeImmutable $date
     * @param string|null                  $parts
     */
    public function increment(DateTimeInterface &$date, $invert = false, $parts = null): FieldInterface
    {
        if (is_null($parts)) {
            $date = $date->modify(($invert ? '-' : '+') . '1 minute');
            return $this;
        }

        $parts = false !== strpos($parts, ',') ? explode(',', $parts) : [$parts];
        $minutes = [];
        foreach ($parts as $part) {
            $minutes = array_merge($minutes, $this->getRangeForExpression($part, 59));
        }

        $current_minute = $date->format('i');
        $position = $invert ? \count($minutes) - 1 : 0;
        if (\count($minutes) > 1) {
            for ($i = 0; $i < \count($minutes) - 1; ++$i) {
                if ((!$invert && $current_minute >= $minutes[$i] && $current_minute < $minutes[$i + 1]) ||
                    ($invert && $current_minute > $minutes[$i] && $current_minute <= $minutes[$i + 1])) {
                    $position = $invert ? $i : $i + 1;

                    break;
                }
            }
        }

        if ((!$invert && $current_minute >= $minutes[$position]) || ($invert && $current_minute <= $minutes[$position])) {
            $date = $date->modify(($invert ? '-' : '+') . '1 hour');
            $date = $date->setTime((int) $date->format('H'), $invert ? 59 : 0);
        } else {
            $date = $date->setTime((int) $date->format('H'), (int) $minutes[$position]);
        }

        return $this;
    }
}

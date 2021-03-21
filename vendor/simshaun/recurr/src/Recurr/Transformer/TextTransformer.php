<?php

namespace Recurr\Transformer;

use Recurr\Rule;

class TextTransformer
{
    protected $fragments = array();
    protected $translator;

    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator ?: new Translator('en');
    }

    public function transform(Rule $rule)
    {
        $this->fragments = array();

        switch ($rule->getFreq()) {
            case 0:
                $this->addYearly($rule);
                break;
            case 1:
                $this->addMonthly($rule);
                break;
            case 2:
                $this->addWeekly($rule);
                break;
            case 3:
                $this->addDaily($rule);
                break;
            case 4:
                $this->addHourly($rule);
                break;
            case 5:
            case 6:
                return $this->translator->trans('Unable to fully convert this rrule to text.');
        }

        $until = $rule->getUntil();
        $count = $rule->getCount();
        if ($until instanceof \DateTimeInterface) {
            $dateFormatted = $this->translator->trans('day_date', array('date' => $until->format('U')));
            $this->addFragment($this->translator->trans('until %date%', array('date' => $dateFormatted)));
        } else if (!empty($count)) {
            if ($this->isPlural($count)) {
                $this->addFragment($this->translator->trans('for %count% times', array('count' => $count)));
            } else {
                $this->addFragment($this->translator->trans('for one time'));
            }
        }

        if (!$this->isFullyConvertible($rule)) {
            $this->addFragment($this->translator->trans('(~ approximate)'));
        }

        return implode(' ', $this->fragments);
    }

    protected function isFullyConvertible(Rule $rule)
    {
        if ($rule->getFreq() >= 5) {
            return false;
        }

        $until = $rule->getUntil();
        $count = $rule->getCount();
        if (!empty($until) && !empty($count)) {
            return false;
        }

        $bySecond = $rule->getBySecond();
        $byMinute = $rule->getByMinute();
        $byHour   = $rule->getByHour();

        if (!empty($bySecond) || !empty($byMinute) || !empty($byHour)) {
            return false;
        }

        $byWeekNum = $rule->getByWeekNumber();
        $byYearDay = $rule->getByYearDay();
        if ($rule->getFreq() != 0 && (!empty($byWeekNum) || !empty($byYearDay))) {
            return false;
        }

        return true;
    }

    protected function addYearly(Rule $rule)
    {
        $interval = $rule->getInterval();
        $byMonth = $rule->getByMonth();
        $byMonthDay = $rule->getByMonthDay();
        $byDay = $rule->getByDay();
        $byYearDay = $rule->getByYearDay();
        $byWeekNum = $rule->getByWeekNumber();

        if (!empty($byMonth) && count($byMonth) > 1 && $interval == 1) {
            $this->addFragment($this->translator->trans('every_month_list'));
        } else {
            $this->addFragment($this->translator->trans($this->isPlural($interval) ? 'every %count% years' : 'every year', array('count' => $interval)));
        }

        $hasNoOrOneByMonth = is_null($byMonth) || count($byMonth) <= 1;
        if ($hasNoOrOneByMonth && empty($byMonthDay) && empty($byDay) && empty($byYearDay) && empty($byWeekNum)) {
            $this->addFragment($this->translator->trans('on'));
            $monthNum = (is_array($byMonth) && count($byMonth)) ? $byMonth[0] : $rule->getStartDate()->format('n');
            $this->addFragment(
                $this->translator->trans('day_month', array('month' => $monthNum, 'day' => $rule->getStartDate()->format('d')))
            );
        } elseif (!empty($byMonth)) {
            if ($interval != 1) {
                $this->addFragment($this->translator->trans('in_month'));
            }

            $this->addByMonth($rule);
        }

        if (!empty($byMonthDay)) {
            $this->addByMonthDay($rule);
            $this->addFragment($this->translator->trans('of_the_month'));
        } else if (!empty($byDay)) {
            $this->addByDay($rule);
        }

        if (!empty($byYearDay)) {
            $this->addFragment($this->translator->trans('on the'));
            $this->addFragment($this->getByYearDayAsText($byYearDay));
            $this->addFragment($this->translator->trans('day'));
        }

        if (!empty($byWeekNum)) {
            $this->addFragment($this->translator->trans('in_week'));
            $this->addFragment($this->translator->trans($this->isPlural(count($byWeekNum)) ? 'weeks' : 'week'));
            $this->addFragment($this->getByWeekNumberAsText($byWeekNum));
        }

        if (empty($byMonthDay) && empty($byYearDay) && empty($byDay) && !empty($byWeekNum)) {
            $this->addDayOfWeek($rule);
        }
    }

    protected function addMonthly(Rule $rule)
    {
        $interval = $rule->getInterval();
        $byMonth = $rule->getByMonth();

        if (!empty($byMonth) && $interval == 1) {
            $this->addFragment($this->translator->trans('every_month_list'));
        } else {
            $this->addFragment($this->translator->trans($this->isPlural($interval) ? 'every %count% months' : 'every month', array('count' => $interval)));
        }

        if (!empty($byMonth)) {
            if ($interval != 1) {
                $this->addFragment($this->translator->trans('in_month'));
            }

            $this->addByMonth($rule);
        }

        $byMonthDay = $rule->getByMonthDay();
        $byDay      = $rule->getByDay();
        if (!empty($byMonthDay)) {
            $this->addByMonthDay($rule);
        } else if (!empty($byDay)) {
            $this->addByDay($rule);
        }
    }

    protected function addWeekly(Rule $rule)
    {
        $interval = $rule->getInterval();
        $byMonth = $rule->getByMonth();
        $byMonthDay = $rule->getByMonthDay();
        $byDay = $rule->getByDay();

        $this->addFragment($this->translator->trans($this->isPlural($interval) ? 'every %count% weeks' : 'every week', array('count' => $interval)));

        if (empty($byMonthDay) && empty($byDay)) {
            $this->addDayOfWeek($rule);
        }

        if (!empty($byMonth)) {
            $this->addFragment($this->translator->trans('in_month'));
            $this->addByMonth($rule);
        }

        if (!empty($byMonthDay)) {
            $this->addByMonthDay($rule);
            $this->addFragment($this->translator->trans('of_the_month'));
        } else if (!empty($byDay)) {
            $this->addByDay($rule);
        }
    }

    protected function addDaily(Rule $rule)
    {
        $interval = $rule->getInterval();
        $byMonth = $rule->getByMonth();

        $this->addFragment($this->translator->trans($this->isPlural($interval) ? 'every %count% days' : 'every day', array('count' => $interval)));

        if (!empty($byMonth)) {
            $this->addFragment($this->translator->trans('in_month'));
            $this->addByMonth($rule);
        }

        $byMonthDay = $rule->getByMonthDay();
        $byDay      = $rule->getByDay();
        if (!empty($byMonthDay)) {
            $this->addByMonthDay($rule);
            $this->addFragment($this->translator->trans('of_the_month'));
        } else if (!empty($byDay)) {
            $this->addByDay($rule);
        }
    }
    
    protected function addHourly(Rule $rule)
    {
        $interval = $rule->getInterval();
        $byMonth = $rule->getByMonth();

        $this->addFragment($this->translator->trans($this->isPlural($interval) ? 'every %count% hours' : 'every hour', array('count' => $interval)));

        if (!empty($byMonth)) {
            $this->addFragment($this->translator->trans('in_month'));
            $this->addByMonth($rule);
        }

        $byMonthDay = $rule->getByMonthDay();
        $byDay      = $rule->getByDay();
        if (!empty($byMonthDay)) {
            $this->addByMonthDay($rule);
            $this->addFragment($this->translator->trans('of_the_month'));
        } else if (!empty($byDay)) {
            $this->addByDay($rule);
        }
    }

    protected function addByMonth(Rule $rule)
    {
        $byMonth = $rule->getByMonth();

        if (empty($byMonth)) {
            return;
        }

        $this->addFragment($this->getByMonthAsText($byMonth));
    }

    protected function addByMonthDay(Rule $rule)
    {
        $byMonthDay = $rule->getByMonthDay();
        $byDay      = $rule->getByDay();

        if (!empty($byDay)) {
            $this->addFragment($this->translator->trans('on'));
            $this->addFragment($this->getByDayAsText($byDay, 'or'));
            $this->addFragment($this->translator->trans('the_for_monthday'));
            $this->addFragment($this->getByMonthDayAsText($byMonthDay, 'or'));
        } else {
            $this->addFragment($this->translator->trans('on the'));
            $this->addFragment($this->getByMonthDayAsText($byMonthDay, 'and'));
        }
    }

    protected function addByDay(Rule $rule)
    {
        $byDay = $rule->getByDay();

        $this->addFragment($this->translator->trans('on'));
        $this->addFragment($this->getByDayAsText($byDay));
    }

    protected function addDayOfWeek(Rule $rule)
    {
        $this->addFragment($this->translator->trans('on'));
        $dayNames = $this->translator->trans('day_names');
        $this->addFragment($dayNames[$rule->getStartDate()->format('w')]);
    }

    public function getByMonthAsText($byMonth)
    {
        if (empty($byMonth)) {
            return '';
        }

        if (count($byMonth) > 1) {
            sort($byMonth);
        }

        $monthNames = $this->translator->trans('month_names');
        $byMonth = array_map(
            function ($monthInt) use ($monthNames) {
                return $monthNames[$monthInt - 1];
            },
            $byMonth
        );

        return $this->getListStringFromArray($byMonth);
    }

    public function getByDayAsText($byDay, $listSeparator = 'and')
    {
        if (empty($byDay)) {
            return '';
        }

        $map = array(
            'SU' => null,
            'MO' => null,
            'TU' => null,
            'WE' => null,
            'TH' => null,
            'FR' => null,
            'SA' => null
        );

        $dayNames = $this->translator->trans('day_names');
        $timestamp = mktime(1, 1, 1, 1, 12, 2014); // A Sunday
        foreach (array_keys($map) as $short) {
            $long        = $dayNames[date('w', $timestamp)];
            $map[$short] = $long;
            $timestamp += 86400;
        }

        $numOrdinals = 0;
        foreach ($byDay as $key => $short) {
            $day    = strtoupper($short);
            $string = '';

            if (preg_match('/([+-]?)([0-9]*)([A-Z]+)/', $short, $parts)) {
                $symbol = $parts[1];
                $nth    = $parts[2];
                $day    = $parts[3];

                if (!empty($nth)) {
                    ++$numOrdinals;
                    $string .= $this->getOrdinalNumber($symbol == '-' ? -$nth : $nth);
                }
            }

            if (!isset($map[$day])) {
                throw new \RuntimeException("byDay $short could not be transformed");
            }

            if (!empty($string)) {
                $string .= ' ';
            }

            $byDay[$key] = ltrim($string.$map[$day]);
        }

        $output = $numOrdinals ? $this->translator->trans('the_for_weekday') . ' ' : '';
        if ($output == ' ') {
            $output = '';
        }
        $output .= $this->getListStringFromArray($byDay, $listSeparator);

        return $output;
    }

    public function getByMonthDayAsText($byMonthDay, $listSeparator = 'and')
    {
        if (empty($byMonthDay)) {
            return '';
        }

        // sort negative indices in reverse order so we get e.g. 1st, 2nd, 4th, 3rd last, last day
        usort($byMonthDay, function ($a, $b) {
            if (($a < 0 && $b < 0) || ($a >= 0 && $b >= 0)) {
                return $a - $b;
            }

            return $b - $a;
        });

        // generate ordinal numbers and insert a "on the" for clarity in the middle if we have both
        // positive and negative ordinals. This is to avoid confusing situations like:
        //
        // monthly on the 1st and 2nd to the last day
        //
        // which gets clarified to:
        //
        // monthly on the 1st day and on the 2nd to the last day
        $hadPositives = false;
        $hadNegatives = false;
        foreach ($byMonthDay as $index => $day) {
            $prefix = '';
            if ($day >= 0) {
                $hadPositives = true;
            }
            if ($day < 0) {
                if ($hadPositives && !$hadNegatives && $listSeparator === 'and') {
                    $prefix = $this->translator->trans('on the') . ' ';
                }
                $hadNegatives = true;
            }
            $byMonthDay[$index] = $prefix . $this->getOrdinalNumber($day, end($byMonthDay) < 0, true);
        }

        return $this->getListStringFromArray($byMonthDay, $listSeparator);
    }

    public function getByYearDayAsText($byYearDay)
    {
        if (empty($byYearDay)) {
            return '';
        }

        // sort negative indices in reverse order so we get e.g. 1st, 2nd, 4th, 3rd last, last day
        usort($byYearDay, function ($a, $b) {
            if (($a < 0 && $b < 0) || ($a >= 0 && $b >= 0)) {
                return $a - $b;
            }

            return $b - $a;
        });

        $byYearDay = array_map(
            array($this, 'getOrdinalNumber'),
            $byYearDay,
            array_fill(0, count($byYearDay), end($byYearDay) < 0)
        );

        return $this->getListStringFromArray($byYearDay);
    }

    public function getByWeekNumberAsText($byWeekNum)
    {
        if (empty($byWeekNum)) {
            return '';
        }

        if (count($byWeekNum) > 1) {
            sort($byWeekNum);
        }

        return $this->getListStringFromArray($byWeekNum);
    }

    protected function addFragment($fragment)
    {
        if ($fragment && $fragment !== ' ') {
            $this->fragments[] = $fragment;
        }
    }

    public function resetFragments()
    {
        $this->fragments = array();
    }

    protected function isPlural($number)
    {
        return $number % 100 != 1;
    }


    protected function getOrdinalNumber($number, $hasNegatives = false, $dayInMonth = false)
    {
        if (!preg_match('{^-?\d+$}D', $number)) {
            throw new \RuntimeException('$number must be a whole number');
        }

        return $this->translator->trans('ordinal_number', array('number' => $number, 'has_negatives' => $hasNegatives, 'day_in_month' => $dayInMonth));
    }

    protected function getListStringFromArray($values, $separator = 'and')
    {
        $separator = $this->translator->trans($separator);

        if (!is_array($values)) {
            throw new \RuntimeException('$values must be an array.');
        }

        $numValues = count($values);

        if (!$numValues) {
            return '';
        }

        if ($numValues == 1) {
            reset($values);

            return current($values);
        }

        if ($numValues == 2) {
            return implode(" $separator ", $values);
        }

        $lastValue = array_pop($values);
        $output    = implode(', ', $values);
        $output .= " $separator ".$lastValue;

        return $output;
    }
}

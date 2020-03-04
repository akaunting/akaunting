<?php

namespace App\Traits;

use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\ArrayTransformerConfig;

trait Recurring
{
    public function createRecurring()
    {
        $request = request();

        if ($request->get('recurring_frequency', 'no') == 'no') {
            return;
        }

        $frequency = ($request['recurring_frequency'] != 'custom') ? $request['recurring_frequency'] : $request['recurring_custom_frequency'];
        $interval = (($request['recurring_frequency'] != 'custom') || ($request['recurring_interval'] < 1)) ? 1 : (int) $request['recurring_interval'];
        $started_at = $request->get('paid_at') ?: ($request->get('invoiced_at') ?: $request->get('billed_at'));

        $this->recurring()->create([
            'company_id' => session('company_id'),
            'frequency' => $frequency,
            'interval' => $interval,
            'started_at' => $started_at,
            'count' => (int) $request['recurring_count'],
        ]);
    }

    public function updateRecurring()
    {
        $request = request();

        if ($request->get('recurring_frequency', 'no') == 'no') {
            $this->recurring()->delete();
            return;
        }

        $frequency = ($request['recurring_frequency'] != 'custom') ? $request['recurring_frequency'] : $request['recurring_custom_frequency'];
        $interval = (($request['recurring_frequency'] != 'custom') || ($request['recurring_interval'] < 1)) ? 1 : (int) $request['recurring_interval'];
        $started_at = $request->get('paid_at') ?: ($request->get('invoiced_at') ?: $request->get('billed_at'));

        $recurring = $this->recurring();

        if ($recurring->count()) {
            $function = 'update';
        } else {
            $function = 'create';
        }

        $recurring->$function([
            'company_id' => session('company_id'),
            'frequency' => $frequency,
            'interval' => $interval,
            'started_at' => $started_at,
            'count' => (int) $request['recurring_count'],
        ]);
    }

    public function current()
    {
        if (!$schedule = $this->schedule()) {
            return false;
        }

        return $schedule->current()->getStart();
    }

    public function next()
    {
        if (!$schedule = $this->schedule()) {
            return false;
        }

        if (!$next = $schedule->next()) {
            return false;
        }

        return $next->getStart();
    }

    public function first()
    {
        if (!$schedule = $this->schedule()) {
            return false;
        }

        return $schedule->first()->getStart();
    }

    public function last()
    {
        if (!$schedule = $this->schedule()) {
            return false;
        }

        return $schedule->last()->getStart();
    }

    public function schedule()
    {
        $config = new ArrayTransformerConfig();
        $config->enableLastDayOfMonthFix();

        $transformer = new ArrayTransformer();
        $transformer->setConfig($config);

        return $transformer->transform($this->getRule());
    }

    public function getRule()
    {
        $rule = (new Rule())
            ->setStartDate($this->getRuleStartDate())
            ->setTimezone($this->getRuleTimeZone())
            ->setFreq($this->getRuleFrequency())
            ->setInterval($this->interval);

        // 0 means infinite
        if ($this->count != 0) {
            $rule->setCount($this->getRuleCount());
        }

        return $rule;
    }

    public function getRuleStartDate()
    {
        return new \DateTime($this->started_at, new \DateTimeZone($this->getRuleTimeZone()));
    }

    public function getRuleTimeZone()
    {
        return setting('localisation.timezone');
    }

    public function getRuleCount()
    {
        // Fix for humans
        return $this->count + 1;
    }

    public function getRuleFrequency()
    {
        return strtoupper($this->frequency);
    }
}

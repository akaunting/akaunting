<?php

namespace App\Traits;

use App\Models\Common\Recurring as Model;
use App\Utilities\Date;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\ArrayTransformerConfig;

trait Recurring
{
    public function createRecurring($request)
    {
        if (empty($request['recurring_frequency']) || ($request['recurring_frequency'] == 'no')) {
            return;
        }

        $frequency = ($request['recurring_frequency'] != 'custom') ? $request['recurring_frequency'] : $request['recurring_custom_frequency'];
        $interval = (($request['recurring_frequency'] != 'custom') || ($request['recurring_interval'] < 1)) ? 1 : (int) $request['recurring_interval'];
        $started_at = !empty($request['recurring_started_at']) ? $request['recurring_started_at'] : Date::now();
        $status = !empty($request['recurring_status']) ? $request['recurring_status'] : Model::ACTIVE_STATUS;
        $limit_by = !empty($request['recurring_limit']) ? $request['recurring_limit'] : 'count';
        $limit_count = isset($request['recurring_limit_count']) ? (int) $request['recurring_limit_count'] : 0;
        $limit_date = !empty($request['recurring_limit_date']) ? $request['recurring_limit_date'] : null;
        $auto_send = !empty($request['recurring_send_email']) ? $request['recurring_send_email'] : 0;
        $source = !empty($request['created_from']) ? $request['created_from'] : source_name();
        $owner = !empty($request['created_by']) ? $request['created_by'] : user_id();

        $this->recurring()->create([
            'company_id'    => $this->company_id,
            'frequency'     => $frequency,
            'interval'      => $interval,
            'started_at'    => $started_at,
            'status'        => $status,
            'limit_by'      => $limit_by,
            'limit_count'   => $limit_count,
            'limit_date'    => $limit_date,
            'auto_send'     => $auto_send,
            'created_from'  => $source,
            'created_by'    => $owner,
        ]);
    }

    public function updateRecurring($request)
    {
        if (empty($request['recurring_frequency']) || ($request['recurring_frequency'] == 'no')) {
            $this->recurring()->delete();

            return;
        }

        $frequency = ($request['recurring_frequency'] != 'custom') ? $request['recurring_frequency'] : $request['recurring_custom_frequency'];
        $interval = (($request['recurring_frequency'] != 'custom') || ($request['recurring_interval'] < 1)) ? 1 : (int) $request['recurring_interval'];
        $started_at = !empty($request['recurring_started_at']) ? $request['recurring_started_at'] : Date::now();
        $limit_by = !empty($request['recurring_limit']) ? $request['recurring_limit'] : 'count';
        $limit_count = isset($request['recurring_limit_count']) ? (int) $request['recurring_limit_count'] : 0;
        $limit_date = !empty($request['recurring_limit_date']) ? $request['recurring_limit_date'] : null;
        $auto_send = !empty($request['recurring_send_email']) ? $request['recurring_send_email'] : 0;

        $recurring = $this->recurring();
        $model_exists = $recurring->count();

        $data = [
            'company_id'    => $this->company_id,
            'frequency'     => $frequency,
            'interval'      => $interval,
            'started_at'    => $started_at,
            'limit_by'      => $limit_by,
            'limit_count'   => $limit_count,
            'limit_date'    => $limit_date,
            'auto_send'     => $auto_send,
        ];

        if (! empty($request['recurring_status'])) {
            $data['status'] = $request['recurring_status'];
        }

        if ($model_exists) {
            $recurring->update($data);
        } else {
            $source = !empty($request['created_from']) ? $request['created_from'] : source_name();
            $owner = !empty($request['created_by']) ? $request['created_by'] : user_id();

            $recurring->create(array_merge($data, [
                'status'        => Model::ACTIVE_STATUS,
                'created_from'  => $source,
                'created_by'    => $owner,
            ]));
        }
    }

    public function getRecurringSchedule()
    {
        $config = new ArrayTransformerConfig();
        $config->enableLastDayOfMonthFix();
        $config->setVirtualLimit($this->getRecurringVirtualLimit());

        $transformer = new ArrayTransformer();
        $transformer->setConfig($config);

        return $transformer->transform($this->getRecurringRule());
    }

    public function getRecurringRule()
    {
        $rule = (new Rule())
            ->setStartDate($this->getRecurringRuleStartDate())
            ->setTimezone($this->getRecurringRuleTimeZone())
            ->setFreq($this->getRecurringRuleFrequency())
            ->setInterval($this->getRecurringRuleInterval());

        if ($this->limit_by == 'date') {
            $rule->setUntil($this->getRecurringRuleUntilDate());
        } elseif ($this->limit_count != 0) {
            // 0 means infinite
            $rule->setCount($this->getRecurringRuleCount());
        }

        return $rule;
    }

    public function getRecurringRuleStartDate()
    {
        return $this->getRecurringRuleDate($this->started_at);
    }

    public function getRecurringRuleUntilDate()
    {
        return $this->getRecurringRuleDate($this->limit_date);
    }

    public function getRecurringRuleTodayDate()
    {
        return $this->getRecurringRuleDate(Date::today()->toDateTimeString());
    }

    public function getRecurringRuleTomorrowDate()
    {
        return $this->getRecurringRuleDate(Date::tomorrow()->toDateTimeString());
    }

    public function getRecurringRuleDate($date)
    {
        return new \DateTime($date, new \DateTimeZone($this->getRecurringRuleTimeZone()));
    }

    public function getRecurringRuleTimeZone()
    {
        return setting('localisation.timezone');
    }

    public function getRecurringRuleCount()
    {
        return $this->limit_count;
    }

    public function getRecurringRuleFrequency()
    {
        return strtoupper($this->frequency);
    }

    public function getRecurringRuleInterval()
    {
        return $this->interval;
    }

    public function getRecurringVirtualLimit()
    {
        switch ($this->frequency) {
            case 'yearly':
                $limit = '2';
                break;
            case 'monthly':
                $limit = '24';
                break;
            case 'weekly':
                $limit = '104';
                break;
            case 'daily':
            default;
                $limit = '732';
                break;
        }

        return $limit;
    }

    public function getNextRecurring()
    {
        if (! $schedule = $this->getRecurringSchedule()) {
            return false;
        }

        $schedule = $schedule->startsAfter($this->getRecurringRuleTodayDate());

        if ($schedule->count() == 0) {
            return false;
        }

        if (! $next = $schedule->current()) {
            return false;
        }

        return $next->getStart();
    }

    public function getFirstRecurring()
    {
        if (! $schedule = $this->getRecurringSchedule()) {
            return false;
        }

        if (! $first = $schedule->first()) {
            return false;
        }

        return $first->getStart();
    }

    public function getLastRecurring()
    {
        if (! $schedule = $this->getRecurringSchedule()) {
            return false;
        }

        if (! $last = $schedule->last()) {
            return false;
        }

        return $last->getStart();
    }
}

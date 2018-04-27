<?php

namespace App\Traits;

trait Recurring
{

    public function createRecurring()
    {
        $request = request();

        if ($request->get('recurring_frequency') == 'no') {
            return;
        }

        $frequency = ($request['recurring_frequency'] != 'custom') ? $request['recurring_frequency'] : $request['recurring_custom_frequency'];
        $interval = ($request['recurring_frequency'] != 'custom') ? 1 : (int) $request['recurring_interval'];
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

        if ($request->get('recurring_frequency') == 'no') {
            $this->recurring()->delete();
            return;
        }

        $frequency = ($request['recurring_frequency'] != 'custom') ? $request['recurring_frequency'] : $request['recurring_custom_frequency'];
        $interval = ($request['recurring_frequency'] != 'custom') ? 1 : (int) $request['recurring_interval'];
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
}
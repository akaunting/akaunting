<?php

namespace App\Traits;

use App\Abstracts\Job;
use App\Exceptions\Common\TooManyEmailsSent;
use App\Traits\Jobs;
use Illuminate\Support\Facades\RateLimiter;

trait Emails
{
    use Jobs;

    public function sendEmail(Job $job): array
    {
        // Check if the user has reached the limit of emails per month
        $key_per_month = 'email-month:' . user()->id;
        $limit_per_month = config('app.throttles.email.month');
        $decay_per_month = 60 * 60 * 24 * 30;

        $can_send = RateLimiter::attempt($key_per_month, $limit_per_month, fn() => '', $decay_per_month);

        if ($can_send) {
            // Check if the user has reached the limit of emails per minute
            $key_per_minute = 'email-minute:' . user()->id;
            $limit_per_minute = config('app.throttles.email.minute');

            $can_send = RateLimiter::attempt($key_per_minute, $limit_per_minute, fn() => '');
        }

        if ($can_send) {
            $this->dispatch($job);

            $response = [
                'success' => true,
                'error' => false,
                'data' => '',
                'message' => '',
            ];

            return $response;
        }

        $response = [
            'success' => false,
            'error' => true,
            'data' => null,
            'message' => 'Too many emails sent!',
        ];

        report(new TooManyEmailsSent('Too many emails sent!'));

        return $response;
    }
}

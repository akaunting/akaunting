<?php

namespace App\Listeners\Email;

use App\Exceptions\Common\TooManyEmailsSent;
use App\Events\Email\TooManyEmailsSent as Event;

class ReportTooManyEmailsSent
{
    public function handle(Event $event): void
    {
        report(new TooManyEmailsSent('Too many emails sent!'));
    }
}

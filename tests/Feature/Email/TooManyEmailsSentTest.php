<?php

namespace Tests\Feature\Email;

use App\Events\Email\TooManyEmailsSent;
use Tests\Feature\FeatureTestCase;

class TooManyEmailsSentTest extends FeatureTestCase
{
    public function testItShouldNotBlockIpDueToTooManyEmailsSent()
    {
        $this->loginAs();

        config(['firewall.middleware.too_many_emails_sent.enabled' => true]);

        for ($i = 0; $i < 19; $i++) {
            event(new TooManyEmailsSent(user_id()));
        }

        $this->assertDatabaseHas('firewall_logs', [
            'user_id' => user_id(),
            'middleware' => 'too_many_emails_sent',
        ]);

        $this->assertDatabaseCount('firewall_logs', 19);

        $this->assertDatabaseEmpty('firewall_ips');
    }

    public function testItShouldBlockIpDueToTooManyEmailsSent()
    {
        $this->loginAs();

        config(['firewall.middleware.too_many_emails_sent.enabled' => true]);

        for ($i = 0; $i < 20; $i++) {
            event(new TooManyEmailsSent(user_id()));
        }

        $this->assertDatabaseHas('firewall_logs', [
            'user_id' => user_id(),
            'middleware' => 'too_many_emails_sent',
        ]);

        $this->assertDatabaseCount('firewall_logs', 20);

        $this->assertDatabaseHas('firewall_ips', [
            'ip' => request()->ip(),
        ]);
    }
}

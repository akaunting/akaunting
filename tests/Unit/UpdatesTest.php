<?php

namespace Tests\Unit;

use App\Utilities\Versions;
use PHPUnit\Framework\TestCase;

class UpdatesTest extends TestCase
{
    public function testItShouldUpdateMiddleListener()
    {
        $listener_version = '1.20';
        $old_version = '1.10';
        $new_version = '1.30';

        $status = Versions::shouldUpdate($listener_version, $old_version, $new_version);

        $this->assertTrue($status);
    }

    public function testItShouldUpdateSameListener()
    {
        $listener_version = '1.30';
        $old_version = '1.10';
        $new_version = '1.30';

        $status = Versions::shouldUpdate($listener_version, $old_version, $new_version);

        $this->assertTrue($status);
    }

    public function testItShouldNotUpdateLowerListener()
    {
        $listener_version = '1.9';
        $old_version = '1.10';
        $new_version = '1.30';

        $status = Versions::shouldUpdate($listener_version, $old_version, $new_version);

        $this->assertFalse($status);
    }

    public function testItShouldNotUpdateGreaterListener()
    {
        $listener_version = '1.40';
        $old_version = '1.10';
        $new_version = '1.30';

        $status = Versions::shouldUpdate($listener_version, $old_version, $new_version);

        $this->assertFalse($status);
    }
}

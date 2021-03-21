<?php

namespace Monooso\Unobserve\Tests;

use Monooso\Unobserve\CanMute;
use Orchestra\Testbench\TestCase;

class CanMuteTest extends TestCase
{
    /** @test */
    public function it_mutes_an_array_of_events()
    {
        CanMuteTarget::mute(['cloaked']);

        $target = resolve(CanMuteTarget::class);

        $this->assertNull($target->cloaked());
        $this->assertSame('uncloaked', $target->uncloaked());
    }

    /** @test */
    public function it_mutes_a_single_event()
    {
        CanMuteTarget::mute('cloaked');

        $target = resolve(CanMuteTarget::class);

        $this->assertNull($target->cloaked());
        $this->assertSame('uncloaked', $target->uncloaked());
    }

    /** @test */
    public function it_mutes_all_events()
    {
        CanMuteTarget::mute();

        $target = resolve(CanMuteTarget::class);

        $this->assertNull($target->cloaked());
        $this->assertNull($target->uncloaked());
    }

    /** @test */
    public function it_unmutes_all_events()
    {
        CanMuteTarget::mute();
        CanMuteTarget::unmute();

        $target = resolve(CanMuteTarget::class);

        $this->assertSame('cloaked', $target->cloaked());
        $this->assertSame('uncloaked', $target->uncloaked());
    }
}

class CanMuteTarget
{
    use CanMute;

    public function cloaked()
    {
        return 'cloaked';
    }

    public function uncloaked()
    {
        return 'uncloaked';
    }
}

<?php

namespace Akaunting\MutableObserver\Tests;

use Akaunting\MutableObserver\Traits\Mutable;
use Orchestra\Testbench\TestCase;

class MutableTest extends TestCase
{
    public function testItMutesAnArrayOfEvents(): void
    {
        UserObserver::mute(['cloaked']);

        $target = app(UserObserver::class);

        $this->assertNull($target->cloaked());
        $this->assertSame('uncloaked', $target->uncloaked());
    }

    public function testItMutesOneEvent(): void
    {
        UserObserver::mute('cloaked');

        $target = app(UserObserver::class);

        $this->assertNull($target->cloaked());
        $this->assertSame('uncloaked', $target->uncloaked());
    }

    public function testItMutesAllEvents(): void
    {
        UserObserver::mute();

        $target = app(UserObserver::class);

        $this->assertNull($target->cloaked());
        $this->assertNull($target->uncloaked());
    }

    public function testItUnmutesAllEvents(): void
    {
        UserObserver::mute();
        UserObserver::unmute();

        $target = app(UserObserver::class);

        $this->assertSame('cloaked', $target->cloaked());
        $this->assertSame('uncloaked', $target->uncloaked());
    }
}

class UserObserver
{
    use Mutable;

    public function cloaked(): string
    {
        return 'cloaked';
    }

    public function uncloaked(): string
    {
        return 'uncloaked';
    }
}

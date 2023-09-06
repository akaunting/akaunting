<?php

namespace Akaunting\MutableObserver\Tests;

use Akaunting\MutableObserver\Proxy;
use BadMethodCallException;
use Orchestra\Testbench\TestCase;

class ProxyTest extends TestCase
{
    public function testItSwallowsCloakedEvents(): void
    {
        $target = new ProxyTarget;

        $actual = (new Proxy($target, ['cloaked']))->cloaked();

        $this->assertNull($actual);
    }

    public function testItPassesUncloakedEventsToTheObserver(): void
    {
        $target = new ProxyTarget;

        $actual = (new Proxy($target, ['cloaked']))->uncloaked();

        $this->assertSame('uncloaked', $actual);
    }

    public function testItSwallowsAllEvents(): void
    {
        $target = new ProxyTarget;
        $proxy = new Proxy($target, ['*']);

        $this->assertNull($proxy->cloaked());
        $this->assertNull($proxy->uncloaked());
    }

    public function testItRaisesAnExceptionForUnknownMethods(): void
    {
        $target = new ProxyTarget;

        $this->expectException(BadMethodCallException::class);

        (new Proxy($target, ['cloaked']))->unknown();
    }
}

class ProxyTarget
{
    public function uncloaked(): string
    {
        return 'uncloaked';
    }

    public function cloaked(): string
    {
        return 'cloaked';
    }
}

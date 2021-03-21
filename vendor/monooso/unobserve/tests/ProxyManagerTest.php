<?php

namespace Monooso\Unobserve\Tests;

use Monooso\Unobserve\Proxy;
use Monooso\Unobserve\ProxyManager;
use Orchestra\Testbench\TestCase;

class ProxyManagerTest extends TestCase
{
    /** @test */
    public function it_registers_a_proxy_with_the_service_container()
    {
        $app = $this->resolveApplication();

        $manager = new ProxyManager($app);
        $manager->register(new ProxyManagerTarget, ['deleted', 'saved']);

        $this->assertInstanceOf(Proxy::class, $app->make(ProxyManagerTarget::class));
    }

    /** @test */
    public function it_unregisters_a_proxy()
    {
        $app = $this->resolveApplication();

        $manager = new ProxyManager($app);
        $manager->unregister(new ProxyManagerTarget);

        $this->assertInstanceOf(ProxyManagerTarget::class, $app->make(ProxyManagerTarget::class));
    }
}

class ProxyManagerTarget
{
}

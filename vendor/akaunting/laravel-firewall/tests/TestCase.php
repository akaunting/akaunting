<?php

namespace Akaunting\Firewall\Tests;

use Akaunting\Firewall\Provider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->setUpConfig();

        $this->artisan('vendor:publish', ['--tag' => 'firewall']);
        $this->artisan('migrate:refresh', ['--database' => 'testbench']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            Provider::class,
        ];
    }

    protected function setUpDatabase()
    {
        config(['database.default' => 'testbench']);

        config(['database.connections.testbench' => [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ],
        ]);
    }

    protected function setUpConfig()
    {
        config(['firewall' => require __DIR__ . '/../src/Config/firewall.php']);

        config(['firewall.notifications.mail.enabled' => false]);
        config(['firewall.middleware.ip.methods' => ['all']]);
        config(['firewall.middleware.lfi.methods' => ['all']]);
        config(['firewall.middleware.rfi.methods' => ['all']]);
        config(['firewall.middleware.sqli.methods' => ['all']]);
        config(['firewall.middleware.xss.methods' => ['all']]);
    }

    public function getNextClosure()
    {
        return function () {
            return 'next';
        };
    }
}

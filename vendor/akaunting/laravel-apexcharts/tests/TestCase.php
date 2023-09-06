<?php

namespace Akaunting\Apexcharts\Tests;

use Akaunting\Apexcharts\Facade;
use Akaunting\Apexcharts\Provider;
use Orchestra\Testbench\TestCase as TestBenchTestCase;

class TestCase extends TestBenchTestCase
{
    /**
     * Sets the env data to interact as env file values
     *
     * @param [type] $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');

        $app['config']->set('database.connection.testing', [
            'driver'    => 'sqlite',
            'database'  => ':memory:'
        ]);
    }

    // set providers to test the class
    protected function getPackageProviders($app): array
    {
        return [
            Provider::class,
        ];
    }

    // With this method I can use the facade instead of all class namespace
    protected function getPackageAliases($app): array
    {
        return [
            'FirstPackage' => Facade::class
        ];
    }
}

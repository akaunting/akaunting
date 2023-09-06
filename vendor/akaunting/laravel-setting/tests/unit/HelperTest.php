<?php

use Illuminate\Container\Container;
use Mockery as m;

class HelperTest extends PHPUnit_Framework_TestCase
{
    public static $functions;

    public function setUp()
    {
        self::$functions = m::mock();

        Container::setInstance(new Container());

        $store = m::mock('Akaunting\Setting\Contracts\Driver');

        app()->bind('setting', function () use ($store) {
            return $store;
        });
    }

    /** @test */
    public function helper_without_parameters_returns_store()
    {
        $this->assertInstanceOf('Akaunting\Setting\Contracts\Driver', setting());
    }

    /** @test */
    public function single_parameter_get_a_key_from_store()
    {
        app('setting')->shouldReceive('get')->with('foo', null)->once();

        setting('foo');
    }

    public function two_parameters_return_a_default_value()
    {
        app('setting')->shouldReceive('get')->with('foo', 'bar')->once();

        setting('foo', 'bar');
    }

    /** @test */
    public function array_parameter_call_set_method_into_store()
    {
        app('setting')->shouldReceive('set')->with(['foo', 'bar'])->once();

        setting(['foo', 'bar']);
    }
}

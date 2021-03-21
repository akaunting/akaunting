<?php

use Akaunting\Setting\Drivers\Database;

abstract class AbstractFunctionalTest extends PHPUnit_Framework_TestCase
{
    abstract protected function createStore(array $data = []);

    protected function assertStoreEquals($store, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->all(), $message);
        $store->save();
        $store = $this->createStore();
        $this->assertEquals($expected, $store->all(), $message);
    }

    protected function assertStoreKeyEquals($store, $key, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->get($key), $message);
        $store->save();
        $store = $this->createStore();
        $this->assertEquals($expected, $store->get($key), $message);
    }

    /** @test */
    public function store_is_initially_empty()
    {
        $store = $this->createStore();
        $this->assertEquals([], $store->all());
    }

    /** @test */
    public function written_changes_are_saved()
    {
        $store = $this->createStore();
        $store->set('foo', 'bar');
        $this->assertStoreKeyEquals($store, 'foo', 'bar');
    }

    /** @test */
    public function nested_keys_are_nested()
    {
        $store = $this->createStore();
        $store->set('foo.bar', 'baz');
        $this->assertStoreEquals($store, ['foo' => ['bar' => 'baz']]);
    }

    /** @test */
    public function cannot_set_nested_key_on_non_array_member()
    {
        $store = $this->createStore();
        $store->set('foo', 'bar');
        $this->setExpectedException('UnexpectedValueException', 'Non-array segment encountered');
        $store->set('foo.bar', 'baz');
    }

    /** @test */
    public function can_forget_key()
    {
        $store = $this->createStore();
        $store->set('foo', 'bar');
        $store->set('bar', 'baz');
        $this->assertStoreEquals($store, ['foo' => 'bar', 'bar' => 'baz']);

        $store->forget('foo');
        $this->assertStoreEquals($store, ['bar' => 'baz']);
    }

    /** @test */
    public function can_forget_nested_key()
    {
        $store = $this->createStore();
        $store->set('foo.bar', 'baz');
        $store->set('foo.baz', 'bar');
        $store->set('bar.foo', 'baz');
        $this->assertStoreEquals($store, [
            'foo' => [
                'bar' => 'baz',
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $store->forget('foo.bar');
        $this->assertStoreEquals($store, [
            'foo' => [
                'baz' => 'bar',
            ],
            'bar' => [
                'foo' => 'baz',
            ],
        ]);

        $store->forget('bar.foo');
        $expected = [
            'foo' => [
                'baz' => 'bar',
            ],
            'bar' => [
            ],
        ];
        if ($store instanceof Database) {
            unset($expected['bar']);
        }
        $this->assertStoreEquals($store, $expected);
    }

    /** @test */
    public function can_forget_all()
    {
        $store = $this->createStore(['foo' => 'bar']);
        $this->assertStoreEquals($store, ['foo' => 'bar']);
        $store->forgetAll();
        $this->assertStoreEquals($store, []);
    }
}

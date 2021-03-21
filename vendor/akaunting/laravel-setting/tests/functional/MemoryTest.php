<?php

class MemoryTest extends AbstractFunctionalTest
{
    protected function assertStoreEquals($store, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->all(), $message);
        // removed persistance test assertions
    }

    protected function assertStoreKeyEquals($store, $key, $expected, $message = null)
    {
        $this->assertEquals($expected, $store->get($key), $message);
        // removed persistance test assertions
    }

    protected function createStore(array $data = null)
    {
        return new \Akaunting\Setting\Drivers\Memory($data);
    }
}

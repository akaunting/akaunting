<?php

class DatabaseTest extends AbstractFunctionalTest
{
    public function setUp()
    {
        $this->container = new \Illuminate\Container\Container();
        $this->capsule = new \Illuminate\Database\Capsule\Manager($this->container);
        $this->capsule->setAsGlobal();
        $this->container['db'] = $this->capsule;
        $this->capsule->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $this->capsule->schema()->create('settings', function ($t) {
            $t->string('key', 64)->unique();
            $t->string('value', 4096);
        });
    }

    public function tearDown()
    {
        $this->capsule->schema()->drop('settings');
        unset($this->capsule);
        unset($this->container);
    }

    protected function createStore(array $data = [])
    {
        if ($data) {
            $store = $this->createStore();
            $store->set($data);
            $store->save();
            unset($store);
        }

        return new \Akaunting\Setting\Drivers\Database(
            $this->capsule->getConnection()
        );
    }
}

<?php

namespace Akaunting\Setting\Drivers;

use Akaunting\Setting\Contracts\Driver;

class Memory extends Driver
{
    /**
     * @param array $data
     */
    public function __construct(array $data = null)
    {
        if ($data) {
            $this->data = $data;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtraColumns()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function read()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $data)
    {
        // do nothing
    }
}

<?php

namespace Dingo\Api\Provider;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

abstract class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Array of config items that are instantiable.
     *
     * @var array
     */
    protected $instantiable = [
        'middleware', 'auth', 'throttling', 'transformer', 'formats',
    ];

    /**
     * Retrieve and instantiate a config value if it exists and is a class.
     *
     * @param string $item
     * @param bool   $instantiate
     *
     * @return mixed
     */
    protected function config($item, $instantiate = true)
    {
        $value = $this->app['config']->get('api.'.$item);

        if (is_array($value)) {
            return $instantiate ? $this->instantiateConfigValues($item, $value) : $value;
        }

        return $instantiate ? $this->instantiateConfigValue($item, $value) : $value;
    }

    /**
     * Instantiate an array of instantiable configuration values.
     *
     * @param string $item
     * @param array  $values
     *
     * @return array
     */
    protected function instantiateConfigValues($item, array $values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = $this->instantiateConfigValue($item, $value);
        }

        return $values;
    }

    /**
     * Instantiate an instantiable configuration value.
     *
     * @param string $item
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function instantiateConfigValue($item, $value)
    {
        if (is_string($value) && in_array($item, $this->instantiable)) {
            return $this->app->make($value);
        }

        return $value;
    }
}

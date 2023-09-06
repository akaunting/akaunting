<?php

namespace Collective\Html;

use BadMethodCallException;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

trait Componentable
{

    /**
     * The registered components.
     *
     * @var array
     */
    protected static $components = [];

    /**
     * Register a custom component.
     *
     * @param       $name
     * @param       $view
     * @param array $signature
     *
     * @return void
     */
    public static function component($name, $view, array $signature)
    {
        static::$components[$name] = compact('view', 'signature');
    }

    /**
     * Check if a component is registered.
     *
     * @param $name
     *
     * @return bool
     */
    public static function hasComponent($name)
    {
        return isset(static::$components[$name]);
    }

    /**
     * Render a custom component.
     *
     * @param        $name
     * @param  array $arguments
     *
     * @return HtmlString
     */
    protected function renderComponent($name, array $arguments)
    {
        $component = static::$components[$name];
        $data = $this->getComponentData($component['signature'], $arguments);

        return new HtmlString(
          $this->view->make($component['view'], $data)->render()
        );
    }

    /**
     * Prepare the component data, while respecting provided defaults.
     *
     * @param  array $signature
     * @param  array $arguments
     *
     * @return array
     */
    protected function getComponentData(array $signature, array $arguments)
    {
        $data = [];

        $i = 0;
        foreach ($signature as $variable => $default) {
            // If the "variable" value is actually a numeric key, we can assume that
            // no default had been specified for the component argument and we'll
            // just use null instead, so that we can treat them all the same.
            if (is_numeric($variable)) {
                $variable = $default;
                $default = null;
            }

            $data[$variable] = Arr::get($arguments, $i, $default);

            $i++;
        }

        return $data;
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return \Illuminate\Contracts\View\View|mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (static::hasComponent($method)) {
            return $this->renderComponent($method, $parameters);
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }
}

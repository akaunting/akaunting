<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use App\Abstracts\View\Components\Form as BaseForm;

class Form extends BaseForm
{
    /** @var string */
    public $method;

    /** @var string */
    public $action;

    public $model;

    /** @var string */
    public $class;

    /** @var string */
    public $role;

    /** @var string */
    public $novalidate;

    /** @var string */
    public $enctype;

    /** @var string */
    public $acceptCharset;

    public $route;

    public $url;

    public $submit;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $method = 'POST',
        string $action = '',
        $model = false,
        string $class = 'mb-0',
        string $role = 'form',
        string $novalidate = 'true',
        string $enctype = 'multipart/form-data',
        string $acceptCharset = 'UTF-8',
        $route = '',
        $url = '',
        $submit = 'onSubmit'
    ) {
        $this->method = Str::upper($method);
        $this->action = $this->getAction($action, $route, $url);
        $this->model = $model;
        $this->class = $class;
        $this->role = $role;
        $this->novalidate = $novalidate;
        $this->enctype = $enctype;
        $this->acceptCharset = $acceptCharset;
        $this->submit = $submit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.index');
    }

    protected function getAction($action, $route, $url)
    {
        if (!empty($action)) {
            return $action;
        }

        if (!empty($route)) {
            return $this->getRouteAction($route);
        }

        if (!empty($url)) {
            return $this->getUrlAction($url);
        }

        return '';
    }

    /**
     * Get the action for a "url" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getUrlAction($options)
    {
        if (is_array($options)) {
            return url($options[0], array_slice($options, 1));
        }

        return url($options);
    }

    /**
     * Get the action for a "route" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getRouteAction($options)
    {
        if (is_array($options)) {
            $parameters = array_slice($options, 1);

            if (array_keys($options) === [0, 1]) {
                $parameters = head($parameters);
            }

            return route($options[0], $parameters);
        }

        return route($options);
    }
}

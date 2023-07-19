<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class DeleteLink extends Component
{
    public $id;

    public $label;

    public $model;

    public $modelId;

    public $modelName;

    public $modelTable;

    /** @var string */
    public $text;

    /** @var string */
    public $type;

    /** @var string */
    public $title;

    /** @var string */
    public $message;

    public $action;

    public $route;

    public $url;

    public $cancelText;

    public $deleteText;

    public $override;

    public $class;

    public $textClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $label = '',
        $model = false, $modelId = 'id', $modelName = 'name', string $modelTable = '',
        $text = '', $type = '',
        $title = '',  $message = '', 
        $action = '', $route = '', $url = '',
        $cancelText = '', $deleteText = '',
        $override = '', $class = '', $textClass = ''
    ) {
        $this->label = $this->getLabel($label);

        $this->model = $model;
        $this->modelId = $modelId;
        $this->modelName = $modelName;
        $this->modelTable = $model->getTable();

        $this->text = $text;
        $this->type = $type;

        $this->action = $this->getAction($action, $route, $url);
        $this->route = $route;
        $this->url = $url;

        $this->title = $this->getTitle($title);
        $this->message = $this->getMessage($message);

        $this->cancelText = $this->getCancelText($cancelText);
        $this->deleteText = $this->getDeleteText($deleteText);

        $this->id = $this->getId();

        $this->override = $override;

        $this->class = $this->getClass($class);
        $this->textClass = ! empty($textClass) ? $textClass : 'w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.delete-link');
    }

    protected function getId()
    {
        if (! empty($this->model)) {
            return $this->model->{$this->modelId};
        }

        if (! empty($this->route) && is_array($this->route)) {
            return $this->route[1];
        }

        return mt_rand();
    }

    protected function getLabel($label)
    {
        if (! empty($label)) {
            return $label;
        }

        return trans('general.delete');
    }

    protected function getAction($action, $route, $url)
    {
        if (! empty($action)) {
            return $action;
        }

        if (! empty($route)) {
            return $this->getRouteAction($route);
        }

        if (! empty($url)) {
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

        if (! empty($this->model)) {
            return url($options, $this->model->{$this->modelId});
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

        if (! empty($this->model)) {
            return route($options, $this->model->{$this->modelId});
        }

        return route($options);
    }

    protected function getTitle($title)
    {
        if (! empty($title)) {
            return $title;
        }

        $type = '';

        if (! empty($this->model)) {
            $type = $this->getModelTitle();
        }

        return ! empty($type) ? trans('general.title.delete', ['type' => $type]) : trans('general.delete');
    }

    protected function getMessage($cancelText)
    {
        if (!empty($cancelText)) {
            return $cancelText;
        }

        $name = '';
        $type = '';

        if (! empty($this->model)) {
            $page = '';

            if (! empty($this->route)) {
                $page = explode('.', $this->route)[0];
            } elseif (! empty($this->url)) {
                $page = explode('/', $this->url)[1];
            }

            $text = $this->text ? $this->text : $page;
            $name = addslashes($this->model->{$this->modelName});
            $name = Str::replace(['\"', '"'], '&quot;', $name);

            $type = mb_strtolower($this->getModelTitle());

            $message = trans('general.delete_confirm', ['name' => '<strong>' . $name . '</strong>', 'type' => $type]);

            return $message;
        }

        return trans('general.delete_confirm', ['name' => '<strong>' . $name . '</strong>', 'type' => $type]);
    }

    protected function getModelTitle()
    {
        if (! empty($this->title)) {
            return $this->title;
        }

        $group = 'core';
        $page = '';

        if (! empty($this->route)) {
            $paths = explode('.', $this->route);

            $page = $paths[0];
        } elseif (! empty($this->url)) {
            $paths = explode('/', $this->url);

            $page = $paths[1];
        }

        $title = trans_choice('general.' . $page, 1);

        if (module($page) != null) {
            $group = $page;
            $page = (! empty($this->route)) ? $paths[1] : $paths[2];

            $title = trans_choice($group . '::general.' . $page, 1);
        }

        return $title;
    }

    protected function getCancelText($cancelText)
    {
        if (! empty($cancelText)) {
            return $cancelText;
        }

        return trans('general.cancel');
    }

    protected function getDeleteText($deleteText)
    {
        if (! empty($deleteText)) {
            return $deleteText;
        }

        return trans('general.delete');
    }

    protected function getClass($class)
    {
        $default_class = 'w-full flex items-center text-red sm:text-purple px-2 h-9 leading-9 whitespace-nowrap';

        $explode = explode(',', $this->override);

        if (count($explode) && in_array('class', $explode)) {
            $default_class = $class;
        } else {
            $default_class .= ' ' . $class;
        }

        return $default_class;
    }
}

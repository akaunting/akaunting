<?php

namespace Akaunting\Menu;

use Closure;
use Collective\Html\HtmlFacade as HTML;
use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property string url
 * @property string route
 * @property string title
 * @property string name
 * @property string icon
 * @property int parent
 * @property array attributes
 * @property bool active
 * @property int order
 */
class MenuItem implements ArrayableContract
{
    /**
     * Array properties.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * The child collections for current menu item.
     *
     * @var array
     */
    protected $childs = [];

    /**
     * The fillable attribute.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'route',
        'fragment',
        'title',
        'name',
        'icon',
        'parent',
        'attributes',
        'active',
        'order',
        'hideWhen',
    ];

    /**
     * The hideWhen callback.
     *
     * @var Closure
     */
    protected $hideWhen;

    /**
     * Constructor.
     *
     * @param array $properties
     */
    public function __construct($properties = [])
    {
        $this->properties = $properties;
        $this->fill($properties);
    }

    /**
     * Set the icon property when the icon is defined in the link attributes.
     *
     * @param array $properties
     *
     * @return array
     */
    protected static function setIconAttribute(array $properties)
    {
        $icon = Arr::get($properties, 'attributes.icon');
        if (!is_null($icon)) {
            $properties['icon'] = $icon;

            Arr::forget($properties, 'attributes.icon');

            return $properties;
        }

        return $properties;
    }

    /**
     * Get random name.
     *
     * @param array $attributes
     *
     * @return string
     */
    protected static function getRandomName(array $attributes)
    {
        return substr(md5(Arr::get($attributes, 'title', Str::random(6))), 0, 5);
    }

    /**
     * Create new static instance.
     *
     * @param array $properties
     *
     * @return static
     */
    public static function make(array $properties)
    {
        $properties = self::setIconAttribute($properties);

        return new static($properties);
    }

    /**
     * Fill the attributes.
     *
     * @param array $attributes
     */
    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Create new menu child item using array.
     *
     * @param $attributes
     *
     * @return $this
     */
    public function child($attributes)
    {
        $this->childs[] = static::make($attributes);

        return $this;
    }

    /**
     * Register new child menu with dropdown.
     *
     * @param $title
     * @param callable $callback
     * @param int $order
     * @param array $attributes
     * @param string|null $fragment
     *
     * @return $this
     */
    public function dropdown($title, \Closure $callback, $order = 0, array $attributes = [], $fragment = null)
    {
        $properties = compact('title', 'order', 'attributes', 'fragment');

        if (func_num_args() === 3) {
            $arguments = func_get_args();

            $title = Arr::get($arguments, 0);
            $attributes = Arr::get($arguments, 2);

            $properties = compact('title', 'attributes');
        }

        $child = static::make($properties);

        call_user_func($callback, $child);

        $this->childs[] = $child;

        return $child;
    }

    /**
     * Create new menu item and set the action to route.
     *
     * @param $route
     * @param $title
     * @param array $parameters
     * @param array $attributes
     * @param string|null $fragment
     *
     * @return MenuItem
     */
    public function route($route, $title, $parameters = [], $order = 0, $attributes = [], $fragment = null)
    {
        if (func_num_args() === 4) {
            $arguments = func_get_args();

            return $this->add([
                'route' => [Arr::get($arguments, 0), Arr::get($arguments, 2)],
                'title' => Arr::get($arguments, 1),
                'attributes' => Arr::get($arguments, 3),
            ]);
        }

        $route = [$route, $parameters];

        return $this->add(compact('route', 'title', 'order', 'attributes', 'fragment'));
    }

    /**
     * Create new menu item  and set the action to url.
     *
     * @param $url
     * @param $title
     * @param array $attributes
     * @param string|null $fragment
     *
     * @return MenuItem
     */
    public function url($url, $title, $order = 0, $attributes = [], $fragment = null)
    {
        if (func_num_args() === 3) {
            $arguments = func_get_args();

            return $this->add([
                'url' => Arr::get($arguments, 0),
                'title' => Arr::get($arguments, 1),
                'attributes' => Arr::get($arguments, 2),
            ]);
        }

        return $this->add(compact('url', 'title', 'order', 'attributes', 'fragment'));
    }

    /**
     * Add new child item.
     *
     * @param array $properties
     *
     * @return $this
     */
    public function add(array $properties)
    {
        $item = static::make($properties);

        $this->childs[] = $item;

        return $item;
    }

    /**
     * Add new divider.
     *
     * @param int $order
     *
     * @return self
     */
    public function addDivider($order = null)
    {
        $item = static::make(['name' => 'divider', 'order' => $order]);

        $this->childs[] = $item;

        return $item;
    }

    /**
     * Alias method instead "addDivider".
     *
     * @param int $order
     *
     * @return MenuItem
     */
    public function divider($order = null)
    {
        return $this->addDivider($order);
    }

    /**
     * Add dropdown header.
     *
     * @param $title
     *
     * @return $this
     */
    public function addHeader($title)
    {
        $item = static::make([
            'name' => 'header',
            'title' => $title,
        ]);

        $this->childs[] = $item;

        return $item;
    }

    /**
     * Same with "addHeader" method.
     *
     * @param $title
     *
     * @return $this
     */
    public function header($title)
    {
        return $this->addHeader($title);
    }

    /**
     * Get childs.
     *
     * @return array
     */
    public function getChilds()
    {
        if (config('menu.ordering')) {
            return collect($this->childs)->sortBy('order')->all();
        }

        return $this->childs;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        if ($this->route !== null) {
            $url = route($this->route[0], $this->route[1]);

            return !is_null($this->fragment) ? $url . '#' . $this->fragment : $url;
        }

        if (empty($this->url)) {
            return url("/#");
        }

        return url($this->url);
    }

    /**
     * Get request url.
     *
     * @return string
     */
    public function getRequest()
    {
        return ltrim(str_replace(url('/'), '', $this->getUrl()), '/');
    }

    /**
     * Get icon.
     *
     * @param null|string $default
     *
     * @return string
     */
    public function getIcon($default = null)
    {
        if ($this->icon !== null && $this->icon !== '') {
            return '<i class="' . $this->icon . '"></i>';
        }
        if ($default === null) {
            return $default;
        }

        return '<i class="' . $default . '"></i>';
    }

    /**
     * Get properties.
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Get HTML attribute data.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        $attributes = $this->attributes ? $this->attributes : [];

        Arr::forget($attributes, ['active', 'icon', 'search_keywords']);

        return HTML::attributes($attributes);
    }

    /**
     * Check is the current item divider.
     *
     * @return bool
     */
    public function isDivider()
    {
        return $this->is('divider');
    }

    /**
     * Check is the current item divider.
     *
     * @return bool
     */
    public function isHeader()
    {
        return $this->is('header');
    }

    /**
     * Check is the current item divider.
     *
     * @param $name
     *
     * @return bool
     */
    public function is($name)
    {
        return $this->name == $name;
    }

    /**
     * Check is the current item has sub menu .
     *
     * @return bool
     */
    public function hasSubMenu()
    {
        return !empty($this->childs);
    }

    /**
     * Same with hasSubMenu.
     *
     * @return bool
     */
    public function hasChilds()
    {
        return $this->hasSubMenu();
    }

    /**
     * Check the active state for current menu.
     *
     * @return mixed
     */
    public function hasActiveOnChild()
    {
        if ($this->inactive()) {
            return false;
        }

        return $this->hasChilds() ? $this->getActiveStateFromChilds() : false;
    }

    /**
     * Get active state from child menu items.
     *
     * @return bool
     */
    public function getActiveStateFromChilds()
    {
        foreach ($this->getChilds() as $child) {
            if ($child->inactive()) {
                continue;
            }

            if ($child->hasChilds()) {
                if ($child->getActiveStateFromChilds()) {
                    return true;
                }
            } elseif ($child->isActive()) {
                return true;
            } elseif ($child->hasRoute() && $child->getActiveStateFromRoute()) {
                return true;
            } elseif ($child->getActiveStateFromUrl()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get inactive state.
     *
     * @return bool
     */
    public function inactive()
    {
        $inactive = $this->getInactiveAttribute();

        if (is_bool($inactive)) {
            return $inactive;
        }

        if ($inactive instanceof \Closure) {
            return call_user_func($inactive);
        }

        return false;
    }

    /**
     * Get active attribute.
     *
     * @return string
     */
    public function getActiveAttribute()
    {
        return Arr::get($this->attributes, 'active');
    }

    /**
     * Get inactive attribute.
     *
     * @return string
     */
    public function getInactiveAttribute()
    {
        return Arr::get($this->attributes, 'inactive');
    }

    /**
     * Get active state for current item.
     *
     * @return mixed
     */
    public function isActive()
    {
        if ($this->inactive()) {
            return false;
        }

        $active = $this->getActiveAttribute();

        if (is_bool($active)) {
            return $active;
        }

        if ($active instanceof \Closure) {
            return call_user_func($active);
        }

        if ($this->hasRoute()) {
            return $this->getActiveStateFromRoute();
        }

        return $this->getActiveStateFromUrl();
    }

    /**
     * Determine the current item using route.
     *
     * @return bool
     */
    protected function hasRoute()
    {
        return !empty($this->route);
    }

    /**
     * Get active status using route.
     *
     * @return bool
     */
    protected function getActiveStateFromRoute()
    {
        $url = str_replace(url('/') . '/', '', $this->getUrl());
        $url = str_replace('#' . (string) $this->fragment, '', $url);

        return $this->checkActiveState($url);
    }

    /**
     * Get active status using request url.
     *
     * @return bool
     */
    protected function getActiveStateFromUrl()
    {
        $url = str_replace('#' . (string) $this->fragment, '', (string) $this->url);

        return $this->checkActiveState($url);
    }

    /**
     * Check the active state.
     *
     * @return bool
     */
    protected function checkActiveState($url)
    {
        if (empty($url) || in_array($url, config('menu.home_urls', ['/']))) {
            return Request::is($url);
        } else {
            return Request::is($url, $url . '/*');
        }
    }

    /**
     * Set order value.
     *
     * @param  int $order
     * @return self
     */
    public function order($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Set hide condition for current menu item.
     *
     * @param  Closure
     * @return boolean
     */
    public function hideWhen(Closure $callback)
    {
        $this->hideWhen = $callback;

        return $this;
    }

    /**
     * Determine whether the menu item is hidden.
     *
     * @return boolean
     */
    public function hidden()
    {
        if (is_null($this->hideWhen)) {
            return false;
        }

        return call_user_func($this->hideWhen) == true;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getProperties();
    }

    /**
     * Get property.
     *
     * @param string $key
     *
     * @return string|null
     */
    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }
}

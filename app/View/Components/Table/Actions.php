<?php

namespace App\View\Components\Table;

use App\Abstracts\View\Component;

class Actions extends Component
{
    public $model;

    /** @var array */
    public $actions;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = false,
        array $actions = []
    ) {
        $this->model = $model;
        $this->actions = $this->getActions($actions);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.table.actions');
    }

    protected function getActions($actions)
    {
        if (empty($actions)) {
            $actions = [];

            if ($this->model && ! empty($this->model->line_actions)) {
                $actions = $this->model->line_actions;
            }
        }

        foreach ($actions as $key => $action) {
            $attributes = [];

            if (! empty($action['attributes'])) {
                $attributes = $action['attributes'];
            }

            $actions[$key]['attributes'] = $this->getAttributes($attributes);
        }

        return $actions;
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function getAttributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (! is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        // This will convert HTML attributes such as "required" to a correct
        // form instead of using incorrect numerics.
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }

        if (is_array($value) && $key === 'class') {
            return 'class=' . implode(' ', $value);
        }

        if (! is_null($value)) {
            return $key . '=' . e($value, false);
        }
    }
}

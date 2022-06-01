<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;
use App\Events\Common\BulkActionsAdding;

class Bulkaction extends Component
{
    public $class;

    public $text = '';

    public $path = '';

    public $actions = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $class, string $text = '', string $path = '', $actions = []
    ) {
        $this->class = $class;
        $this->text = $text;
        $this->path = $path;
        $this->actions = $this->getActions($actions);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.bulkaction.index');
    }

    protected function getActions($actions)
    {
        if (! empty($actions)) {
            return $actions;
        }

        if (class_exists($this->class)) {
            $bulk_action = app($this->class);

            event(new BulkActionsAdding($bulk_action));

            $this->text = $bulk_action->text;

            if (is_array($bulk_action->path)) {
                $this->path = route('bulk-actions.action', $bulk_action->path);
            } else {
                $this->path = url('common/bulk-actions/' . $bulk_action->path);
            }
        } else {
            $bulk_action = new \stdClass();
            $bulk_action->text = '';
            $bulk_action->path = '';
            $bulk_action->actions = [];
            $bulk_action->icons = [];

            event(new BulkActionsAdding($bulk_action));

            if (is_array($bulk_action->path)) {
                $this->path = route('bulk-actions.action', $bulk_action->path);
            } else {
                $this->path = url('common/bulk-actions/' . $bulk_action->path);
            }
        }

        $actions = [];

        if ($bulk_action->actions) {
            foreach ($bulk_action->actions as $key => $action) {
                if ((isset($action['permission']) && ! user()->can($action['permission']))) {
                    continue;
                }

                if (empty($action['icon']) && array_key_exists($key, $bulk_action->icons)) {
                    $action['icon'] = $bulk_action->icons[$key];
                }

                $actions[$key] = $action;
            }
        }

        return $actions;
    }
}

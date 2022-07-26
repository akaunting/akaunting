<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Setting\Category as Model;

class Category extends Form
{
    public $type = 'income';

    public $path;

    public $remoteAction;

    public $categories;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'category_id';
        }

        $this->path = route('modals.categories.create', ['type' => $this->type]);
        $this->remoteAction = route('categories.index', ['search' => 'type:' . $this->type . ' enabled:1']);

        $this->categories = Model::type($this->type)->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        $model = $this->getParentData('model');

        if (! empty($model)) {
            $this->selected = $model->category_id;

            if (! $this->categories->has($model->category_id) && ($category = $model->category)) {
                $this->categories->put($category->id, $category->name);
            }
        }

        if (empty($this->selected) && (in_array($this->type, [Model::INCOME_TYPE, Model::EXPENSE_TYPE]))) {
            $this->selected = setting('default.' . $this->type . '_category');
        }

        return view('components.form.group.category');
    }
}

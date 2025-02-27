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

        $this->categories = Model::type($this->type)->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();

        $model = $this->getParentData('model');

        $category_id = old('category.id', old('category_id', null));

        if (! empty($category_id)) {
            $this->selected = $category_id;

            $has_category = $this->categories->search(function ($category, int $key) use ($category_id) {
                return $category->id === $category_id;
            });

            if ($has_category === false) {
                $category = Model::find($category_id);

                $this->categories->push($category);
            }
        }

        if (! empty($model) && ! empty($model->category_id)) {
            $this->selected = $model->category_id;

            $selected_category = $model->category;
        }

        if ($this->selected === null && in_array($this->type, [Model::INCOME_TYPE, Model::EXPENSE_TYPE])) {
            $this->selected = setting('default.' . $this->type . '_category');

            $selected_category = Model::find($this->selected);
        }

        if (! empty($selected_category)) {
            $selected_category_id = $selected_category->id;

            $has_selected_category = $this->categories->search(function ($category, int $key) use ($selected_category_id) {
                return $category->id === $selected_category_id;
            });

            if ($has_selected_category === false) {
                $this->categories->push($selected_category);
            }
        }

        return view('components.form.group.category');
    }
}

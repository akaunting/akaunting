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
        $type = $this->type;

        if (empty($this->name)) {
            $this->name = 'category_id';
        }

        $this->path = route('modals.categories.create', ['type' => $this->type]);
        $this->remoteAction = route('categories.index', ['search' => 'type:' . $this->type . ' enabled:1']);

        $this->categories = Model::type($type)->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();

        if (!empty($model) && $model->category && ! $this->categories->has($model->category_id)) {
            $this->categories->put($model->category->id, $model->category->name);
        }

        if($model = $this->getParentData('model')) {
            $this->selected = $model->category_id;
        }

        if (empty($this->selected) && (in_array($type, ['income', 'expense']))) {
            $this->selected = setting('default.' . $type . '_category');
        }

        return view('components.form.group.category');
    }
}

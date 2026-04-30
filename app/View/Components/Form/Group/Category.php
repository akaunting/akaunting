<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Setting\Category as Model;
use App\Traits\Categories;
use App\Traits\Modules;
use Illuminate\Support\Arr;

class Category extends Form
{
    use Categories, Modules;

    public $type = Model::INCOME_TYPE;

    public $path;

    public $remoteAction;

    public $categories;

    /** @var bool */
    public $group;

    public $option_field = [
        'key' => 'id',
        'value' => 'name',
    ];

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

        $types = $this->getTypeCategoryTypes($this->type);
        $types_string = implode(',', $types);

        $this->path = route('modals.categories.create', ['type' => $this->type]);
        $this->remoteAction = route('categories.index', ['search' => 'type:' . $types_string . ' enabled:1']);

        $typeLabels = $this->getCategoryTypes(types: $types);

        $is_code = false;

        foreach (config('type.category', []) as $type => $config) {
            if (! in_array($type, $types)) {
                continue;
            }

            if (empty($config['hide']) || ! in_array('code', $config['hide'])) {
                $is_code = true;
                $this->group = true;
                break;
            }
        }

        $order_by = $is_code ? 'code' : 'name';

        if ($this->group) {
            $this->option_field = [
                'key' => 'id',
                'value' => 'title',
            ];
        }

        $query = Model::type($types);

        $query->enabled()
            ->orderBy($order_by);

        if (! $this->group) {
            $query->take(setting('default.select_limit'));
        }

        $this->categories = $query->get();

        if ($this->group) {
            $groups = [];

            foreach ($this->categories as $category) {
                $group = $typeLabels[$category->type] ?? trans_choice('general.others', 1);

                $category->title = ($category->code ? $category->code . ' - ' : '') . $category->name;
                $category->group = $group;

                $groups[$group][$category->id] = $category;
            }

            ksort($groups);

            $this->categories = $groups;
        }

        $model = $this->getParentData('model');
        $selected_category = null;

        $categoryExists = function ($categoryId): bool {
            if (! $this->group) {
                return $this->categories->contains(function ($category) use ($categoryId) {
                    return (int) $category->id === (int) $categoryId;
                });
            }

            foreach ($this->categories as $group_categories) {
                foreach ($group_categories as $category) {
                    if ((int) $category->id === (int) $categoryId) {
                        return true;
                    }
                }
            }

            return false;
        };

        $appendCategory = function ($category) use ($typeLabels): void {
            if (empty($category)) {
                return;
            }

            $category->title = ($category->code ? $category->code . ' - ' : '') . $category->name;

            if (! $this->group) {
                $this->categories->push($category);

                return;
            }

            $group = $typeLabels[$category->type] ?? trans_choice('general.others', 1);

            if (! isset($this->categories[$group])) {
                $this->categories[$group] = [];
            }

            $this->categories[$group][$category->id] = $category;

            ksort($this->categories);
        };

        $category_id = old('category.id', old('category_id', null));

        if (! empty($category_id)) {
            $this->selected = $category_id;

            if (! $categoryExists($category_id)) {
                $category = Model::find($category_id);

                $appendCategory($category);
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

        if (empty($selected_category) && ! empty($this->selected)) {
            $selected_category = Model::find($this->selected);
        }

        if (! empty($selected_category)) {
            $selected_category_id = $selected_category->id;

            if (! $categoryExists($selected_category_id)) {
                $appendCategory($selected_category);
            }
        }

        return view('components.form.group.category');
    }
}

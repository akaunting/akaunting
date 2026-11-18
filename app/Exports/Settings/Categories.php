<?php

namespace App\Exports\Settings;

use App\Abstracts\Export;
use App\Http\Requests\Setting\Category as Request;
use App\Models\Setting\Category as Model;

class Categories extends Export
{
    public $request_class = Request::class;

    public function collection()
    {
        return $this->parentsFirst(Model::collectForExport($this->ids)->collect());
    }

    public function map($model): array
    {
        // A sub-category can be the parent too.
        $model->parent_name = Model::withSubCategory()->find($model->parent_id)?->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'code',
            'name',
            'type',
            'color',
            'description',
            'parent_name',
            'enabled',
        ];
    }

    /**
     * The import finds a row's parent among the categories there before it, so write every category after its
     * parent, keeping the requested order within each level.
     */
    protected function parentsFirst($categories)
    {
        $parent_ids = Model::withSubCategory()->pluck('parent_id', 'id');

        $level = function ($category) use ($parent_ids) {
            $level = 0;
            $parent_id = $category->parent_id;

            // The bound guards against a parent loop.
            while (! empty($parent_id) && $level < $parent_ids->count()) {
                $level++;
                $parent_id = $parent_ids->get($parent_id);
            }

            return $level;
        };

        return $categories->sortBy($level)->values();
    }
}

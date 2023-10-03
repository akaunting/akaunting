<?php

namespace App\BulkActions\Settings;

use App\Abstracts\BulkAction;
use App\Jobs\Setting\DeleteCategory;
use App\Jobs\Setting\UpdateCategory;
use App\Models\Setting\Category;
use App\Traits\Categories as Helper;

class Categories extends BulkAction
{
    use Helper;

    public $model = Category::class;

    public $text = 'general.categories';

    public $path = [
        'group' => 'settings',
        'type' => 'categories',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-settings-categories',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-settings-categories',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-settings-categories',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-settings-categories',
        ],
    ];

    public function edit($request)
    {
        $selected = $this->getSelectedInput($request);
        $types = $this->getCategoryTypes();

        return $this->response('bulk-actions.settings.categories.edit', compact('selected', 'types'));
    }

    public function update($request)
    {
        $categories = $this->getSelectedRecords($request);

        foreach ($categories as $category) {
            try {
                $request->merge([
                    'enabled' => $category->enabled,
                ]); // for update job authorize..

                $this->dispatch(new UpdateCategory($category, $this->getUpdateRequest($request)));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable($request)
    {
        $categories = $this->getSelectedRecords($request);

        foreach ($categories as $category) {
            try {
                $this->dispatch(new UpdateCategory($category, $request->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $categories = $this->getSelectedRecords($request);

        foreach ($categories as $category) {
            try {
                $this->dispatch(new DeleteCategory($category));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function getSelectedRecords($request, $relationships = null)
    {
        if (empty($relationships)) {
            $model = $this->model::query();
        } else {
            $relationships = Arr::wrap($relationships);

            $model = $this->model::with($relationships);
        }

        return $model->getWithoutChildren()->find($this->getSelectedInput($request));
    }
}

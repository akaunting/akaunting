<?php

namespace App\BulkActions\Settings;

use App\Abstracts\BulkAction;
use App\Jobs\Setting\DeleteCategory;
use App\Jobs\Setting\UpdateCategory;
use App\Models\Setting\Category;

class Categories extends BulkAction
{
    public $model = Category::class;

    public $text = 'general.categories';

    public $path = [
        'group' => 'settings',
        'type' => 'categories',
    ];

    public $actions = [
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
}

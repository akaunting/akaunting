<?php

namespace App\BulkActions\Common;

use App\Abstracts\BulkAction;
use App\Exports\Common\Items as Export;
use App\Jobs\Common\DeleteItem;
use App\Models\Common\Item;

class Items extends BulkAction
{
    public $model = Item::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'path' =>  ['group' => 'common', 'type' => 'items'],
            'type' => '*',
            'permission' => 'update-common-items',
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'path' =>  ['group' => 'common', 'type' => 'items'],
            'type' => '*',
            'permission' => 'update-common-items',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-common-items',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
            'type' => 'download',
        ],
    ];

    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request, 'taxes');

        foreach ($items as $item) {
            try {
                $this->dispatch(new DeleteItem($item));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return $this->exportExcel(new Export($selected), trans_choice('general.items', 2));
    }
}

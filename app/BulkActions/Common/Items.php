<?php

namespace App\BulkActions\Common;

use App\Abstracts\BulkAction;
use App\Exports\Common\Items as Export;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\UpdateItem;
use App\Models\Common\Item;

class Items extends BulkAction
{
    public $model = Item::class;

    public $text = 'general.items';

    public $path = [
        'group' => 'common',
        'type' => 'items',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-common-items',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'path'          => ['group' => 'common', 'type' => 'items'],
            'type'          => '*',
            'permission'    => 'update-common-items',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'path'          => ['group' => 'common', 'type' => 'items'],
            'type'          => '*',
            'permission'    => 'update-common-items',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-common-items',
        ],
        'export'    => [
            'icon'          => 'file_download',
            'name'          => 'general.export',
            'message'       => 'bulk_actions.message.export',
            'type'          => 'download',
        ],
    ];

    public function edit($request)
    {
        $selected = $this->getSelectedInput($request);

        return $this->response('bulk-actions.common.items.edit', compact('selected'));
    }

    public function update($request)
    {
        $items = $this->getSelectedRecords($request, 'taxes');

        foreach ($items as $item) {
            try {
                $this->dispatch(new UpdateItem($item, $this->getUpdateRequest($request)));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

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

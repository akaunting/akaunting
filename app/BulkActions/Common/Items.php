<?php

namespace App\BulkActions\Common;

use App\Abstracts\BulkAction;
use App\Exports\Common\Items as Export;
use App\Models\Common\Item;

class Items extends BulkAction
{

    public $model = Item::class;

    public $actions = [
        'enable' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-common-items'
        ],
        'disable' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-common-items'
        ],
        'duplicate' => [
            'name' => 'general.duplicate',
            'message' => 'bulk_actions.message.duplicate',
            'permission' => 'create-common-items',
            'multiple' => true
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.exports',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-common-items'
        ]
    ];

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     *
     * @return Response
     */
    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $items = $this->model::find($selected);

        foreach ($items as $item) {
            $relationships = $this->countRelationships($item, [
                'invoice_items' => 'invoices',
                'bill_items' => 'bills',
            ]);

            if (empty($relationships)) {
                $item->delete();

                $message = trans('messages.success.deleted', ['type' => $item->name]);

                return new Response($message);
            } else {
                $message = trans('messages.warning.deleted', ['name' => $item->name, 'text' => implode(', ', $relationships)]);

                $this->response->errorUnauthorized($message);
            }
        }
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export($request)
    {
        $selected = $request->get('selected', []);

        return \Excel::download(new Export($selected), trans_choice('general.items', 2) . '.xlsx');
    }
}

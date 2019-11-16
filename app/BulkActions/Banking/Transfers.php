<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Models\Banking\Transfer;

class Transfers extends BulkAction
{

    public $model = Transfer::class;

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_action.message.deletes',
            'permission' => 'delete-banking-transfers'
        ]
    ];

    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $transfers = $this->model::find($selected);

        foreach ($transfers as $transfer) {
            $this->deleteRelationships($transfer, ['expense_transaction', 'income_transaction']);

            $transfer->delete();
        }
    }
}

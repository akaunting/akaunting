<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Jobs\Banking\DeleteTransfer;
use App\Models\Banking\Transfer;

class Transfers extends BulkAction
{
    public $model = Transfer::class;

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-banking-transfers',
        ],
    ];

    public function destroy($request)
    {
        $transfers = $this->getSelectedRecords($request);

        foreach ($transfers as $transfer) {
            try {
                $this->dispatch(new DeleteTransfer($transfer));
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }
    }
}

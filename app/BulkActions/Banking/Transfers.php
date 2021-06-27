<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Jobs\Banking\DeleteTransfer;
use App\Models\Banking\Transfer;
use App\Exports\Banking\Transfers as Export;

class Transfers extends BulkAction
{
    public $model = Transfer::class;

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-banking-transfers',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
            'type' => 'download',
        ],
    ];

    public function destroy($request)
    {
        $transfers = $this->getSelectedRecords($request, [
            'expense_transaction', 'income_transaction'
        ]);

        foreach ($transfers as $transfer) {
            try {
                $this->dispatch(new DeleteTransfer($transfer));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return $this->exportExcel(new Export($selected), trans_choice('general.transfers', 2));
    }
}

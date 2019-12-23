<?php

namespace App\BulkActions\Incomes;

use App\Abstracts\BulkAction;
use App\Exports\Incomes\Revenues as Export;
use App\Models\Banking\Transaction;

class Revenues extends BulkAction
{
    public $model = Transaction::class;

    public $actions = [
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
        ],
        'duplicate' => [
            'name' => 'general.duplicate',
            'message' => 'bulk_actions.message.duplicate',
            'permission' => 'create-incomes-revenues',
            'multiple' => true,
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-incomes-revenues',
        ],
    ];

    public function destroy($request)
    {
        $this->deleteTransactions($request);
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return \Excel::download(new Export($selected), trans_choice('general.revenues', 2) . '.xlsx');
    }
}

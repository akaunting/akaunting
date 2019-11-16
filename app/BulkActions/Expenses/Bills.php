<?php

namespace App\BulkActions\Expenses;

use App\Abstracts\BulkAction;
use App\Exports\Expenses\Bills as Export;
use App\Models\Expense\Bill;
use App\Models\Expense\BillHistory;

class Bills extends BulkAction
{

    public $model = Bill::class;

    public $actions = [
        'received' => [
            'name' => 'general.received',
            'message' => '',
            'permission' => 'update-expenses-bills'
        ],
        'duplicate' => [
            'name' => 'general.duplicate',
            'message' => 'bulk_actions.message.duplicate',
            'permission' => 'create-expenses-bills',
            'multiple' => true
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.exports',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-expenses-bills'
        ]
    ];

    public function duplicate($request)
    {
        $selected = $request->get('selected', []);

        $bills = $this->model::find($selected);

        foreach ($bills as $bill) {
            $clone = $bill->duplicate();

            // Add bill history
            BillHistory::create([
                'company_id' => session('company_id'),
                'bill_id' => $clone->id,
                'status_code' => 'draft',
                'notify' => 0,
                'description' => trans('messages.success.added', ['type' => $clone->bill_number]),
            ]);
        }
    }

    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $bills = $this->model::find($selected);

        foreach ($bills as $bill) {
            $this->deleteRelationships($bill, ['items', 'item_taxes', 'histories', 'payments', 'recurring', 'totals']);
            $bill->delete();
        }
    }

    public function export($request)
    {
        $selected = $request->get('selected', []);

        return \Excel::download(new Export($selected), trans_choice('general.bills', 2) . '.xlsx');
    }

    public function received($request)
    {
        $selected = $request->get('selected', []);

        $bills = $this->model::find($selected);

        foreach ($bills as $bill) {
            $bill->bill_status_code = 'received';
            $bill->save();

            // Add bill history
            BillHistory::create([
                'company_id' => $bill->company_id,
                'bill_id' => $bill->id,
                'status_code' => 'received',
                'notify' => 0,
                'description' => trans('bills.mark_recevied'),
            ]);
        }
    }
}

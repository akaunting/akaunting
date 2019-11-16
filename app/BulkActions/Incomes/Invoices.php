<?php

namespace App\BulkActions\Incomes;

use App\Abstracts\BulkAction;
use App\Events\Income\InvoiceCreated;
use App\Events\Income\InvoiceSent;
use App\Events\Income\PaymentReceived;
use App\Exports\Incomes\Invoices as Export;
use App\Models\Income\Invoice;
use Date;

class Invoices extends BulkAction
{
    public $model = Invoice::class;

    public $actions = [
        'paid' => [
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-incomes-invoices'
        ],
        'sent' => [
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-incomes-invoices'
        ],
        'duplicate' => [
            'name' => 'general.duplicate',
            'message' => 'bulk_actions.message.duplicate',
            'permission' => 'create-incomes-invoices',
            'multiple' => true
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.exports',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-incomes-invoices'
        ]
    ];

    public function duplicate($request)
    {
        $selected = $request->get('selected', []);

        $invoices = $this->model::find($selected);

        foreach ($invoices as $invoice) {
            $clone = $invoice->duplicate();

            event(new InvoiceCreated($clone));
        }
    }

    public function delete($request)
    {
        $this->destroy($request);
    }

    public function destroy($request)
    {
        $selected = $request->get('selected', []);

        $invoices = $this->model::find($selected);

        foreach ($invoices as $invoice) {
            $this->deleteRelationships($invoice, ['items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals']);
            $invoice->delete();
        }
    }

    public function export($request)
    {
        $selected = $request->get('selected', []);

        return \Excel::download(new Export($selected), trans_choice('general.invoices', 2) . '.xlsx');
    }

    public function sent($request)
    {
        $selected = $request->get('selected', []);

        $invoices = $this->model::find($selected);

        foreach ($invoices as $invoice) {
            event(new InvoiceSent($invoice));

            $message = trans('invoices.messages.marked_sent');
        }
    }

    public function paid($request)
    {
        $selected = $request->get('selected', []);

        $invoices = $this->model::find($selected);

        foreach ($invoices as $invoice) {
            event(new PaymentReceived($invoice, []));
        }
    }
}

<?php

namespace App\BulkActions\Sales;

use App\Abstracts\BulkAction;
use App\Events\Document\DocumentCancelled;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentSent;
use App\Events\Document\PaymentReceived;
use App\Exports\Sales\Invoices as Export;
use App\Jobs\Document\DeleteDocument;
use App\Models\Document\Document;

class Invoices extends BulkAction
{
    public $model = Document::class;

    public $actions = [
        'paid' => [
            'name' => 'invoices.mark_paid',
            'message' => 'bulk_actions.message.paid',
            'permission' => 'update-sales-invoices',
        ],
        'sent' => [
            'name' => 'invoices.mark_sent',
            'message' => 'bulk_actions.message.sent',
            'permission' => 'update-sales-invoices',
        ],
        'cancelled' => [
            'name' => 'general.cancel',
            'message' => 'bulk_actions.message.cancelled',
            'permission' => 'update-sales-invoices',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-sales-invoices',
        ],
        'export' => [
            'name' => 'general.export',
            'message' => 'bulk_actions.message.export',
            'type' => 'download',
        ],
    ];

    public function paid($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            if ($invoice->status == 'paid') {
                continue;
            }

            event(new PaymentReceived($invoice, ['type' => 'income']));
        }
    }

    public function sent($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            if ($invoice->status == 'sent') {
                continue;
            }

            event(new DocumentSent($invoice));
        }
    }

    public function cancelled($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            if ($invoice->status == 'cancelled') {
                continue;
            }

            event(new DocumentCancelled($invoice));
        }
    }

    public function duplicate($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            $clone = $invoice->duplicate();

            event(new DocumentCreated($clone, $request));
        }
    }

    public function destroy($request)
    {
        $invoices = $this->getSelectedRecords($request, [
            'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
        ]);

        foreach ($invoices as $invoice) {
            try {
                $this->dispatch(new DeleteDocument($invoice));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function export($request)
    {
        $selected = $this->getSelectedInput($request);

        return $this->exportExcel(new Export($selected), trans_choice('general.invoices', 2));
    }
}

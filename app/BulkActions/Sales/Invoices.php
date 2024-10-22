<?php

namespace App\BulkActions\Sales;

use App\Abstracts\BulkAction;
use App\Models\Document\Document;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Common\CreateZipForDownload;
use App\Jobs\Document\UpdateDocument;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentCancelled;
use App\Events\Document\DocumentMarkedSent;
use App\Exports\Sales\Invoices\Invoices as Export;

class Invoices extends BulkAction
{
    public $model = Document::class;

    public $text = 'general.invoices';

    public $path = [
        'group' => 'sales',
        'type' => 'invoices',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-sales-invoices',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'sent' => [
            'icon'          => 'send',
            'name'          => 'invoices.mark_sent',
            'message'       => 'bulk_actions.message.sent',
            'permission'    => 'update-sales-invoices',
        ],
        'cancelled' => [
            'icon'          => 'cancel',
            'name'          => 'documents.actions.cancel',
            'message'       => 'bulk_actions.message.cancelled',
            'permission'    => 'update-sales-invoices',
        ],
        'delete' => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-sales-invoices',
        ],
        'export' => [
            'icon'          => 'file_download',
            'name'          => 'general.export',
            'message'       => 'bulk_actions.message.export',
            'type'          => 'download',
        ],
        'download' => [
            'icon'          => 'download',
            'name'          => 'general.download',
            'message'       => 'bulk_actions.message.download',
            'type'          => 'download',
        ],
    ];

    public function edit($request)
    {
        $selected = $this->getSelectedInput($request);

        return $this->response('bulk-actions.sales.invoices.edit', compact('selected'));
    }

    public function update($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            try {
                $discount = $invoice->totals->where('code', 'discount')->makeHidden('title')->pluck('amount')->first();

                // for extra total rows..
                $totals = $invoice->totals()->whereNotIn('code', ['sub_total', 'total', 'tax', 'discount'])->get()->toArray();

                $request->merge([
                    'items' => $invoice->items->toArray(),
                    'uploaded_attachment' => $invoice->attachment,
                    'category_id' => ($request->get('category_id')) ?? $invoice->category_id,
                    'discount' => $discount,
                    'totals' => $totals,
                ])->except([

                ]);

                $this->dispatch(new UpdateDocument($invoice, $this->getUpdateRequest($request)));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function sent($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            if ($invoice->status == 'sent') {
                continue;
            }

            event(new DocumentMarkedSent($invoice));
        }
    }

    public function cancelled($request)
    {
        $invoices = $this->getSelectedRecords($request);

        foreach ($invoices as $invoice) {
            if (in_array($invoice->status, ['cancelled', 'draft'])) {
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

    public function download($request)
    {
        $selected = $this->getSelectedRecords($request);

        $file_name = Document::INVOICE_TYPE . '-'. date('Y-m-d-H-i-s');

        $class = '\App\Jobs\Document\DownloadDocument';

        return $this->downloadPdf($selected, $class, $file_name, trans_choice('general.invoices', 2));
    }
}
   
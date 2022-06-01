<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Models\Document\Document;
use Illuminate\Support\Facades\URL;

class InvoiceShare extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-sales-invoices')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-sales-invoices')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-sales-invoices')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-sales-invoices')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Document  $invoice
     *
     * @return Response
     */
    public function create(Document $invoice)
    {
        $page = config('type.document.' . $invoice->type . '.route.prefix');
        $alias = config('type.document.' . $invoice->type . '.alias');

        $route = '';

        if (! empty($alias)) {
            $route .= $alias . '.';
        }

        $preview_route = $route . 'preview.' . $page . '.show';
        $signed_route = $route . 'signed.' . $page . '.show';

        try {
            $previewUrl = route($preview_route, $invoice->id);
        } catch (\Exception $e) {
            $previewUrl = '';
        }

        try {
            route($signed_route, [$this->document->id, 'company_id' => company_id()]);

            $signedUrl = URL::signedRoute($signed_route, [$invoice->id]);
        } catch (\Exception $e) {
            $signedUrl = URL::signedRoute('signed.invoices.show', [$invoice->id]);
        }

        $html = view('modals.invoices.share', compact('invoice', 'previewUrl', 'signedUrl'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('general.title.new', ['type' => trans('general.share_link')]),
                'success_message' => trans('invoices.share.success_message'),
                'buttons' => [
                    'cancel' => [
                        'text' => trans('general.cancel'),
                        'class' => 'btn-outline-secondary',
                    ],
                    'confirm' => [
                        'text' => trans('general.copy_link'),
                        'class' => 'disabled:bg-green-100',
                    ],
                ]
            ]
        ]);
    }
}

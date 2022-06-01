<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Transaction;
use Illuminate\Support\Facades\URL;

class TransactionShare extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-banking-transactions')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-banking-transactions')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-banking-transactions')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-banking-transactions')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Transaction  $transaction
     *
     * @return Response
     */
    public function create(Transaction $transaction)
    {
        $alias = config('type.transaction.' . $transaction->type . '.alias');

        $route = '';

        if (! empty($alias)) {
            $route .= $alias . '.';
        }

        $preview_route = $route . 'preview.payments.show';
        $signed_route = $route . 'signed.payments.show';

        try {
            $previewUrl = route($preview_route, $transaction->id);
        } catch (\Exception $e) {
            $previewUrl = '';
        }

        try {
            route($signed_route, [$this->document->id, 'company_id' => company_id()]);

            $signedUrl = URL::signedRoute($signed_route, [$transaction->id]);
        } catch (\Exception $e) {
            $signedUrl = URL::signedRoute('signed.payments.show', [$transaction->id]);
        }

        $html = view('modals.transactions.share', compact('transaction', 'previewUrl', 'signedUrl'))->render();

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

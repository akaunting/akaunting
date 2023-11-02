<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Transaction;
use App\Notifications\Portal\PaymentReceived as PaymentReceivedNotification;
use App\Notifications\Banking\Transaction as TransactionNotification;
use App\Jobs\Banking\SendTransactionAsCustomMail;
use App\Http\Requests\Common\CustomMail as Request;
use Illuminate\Http\JsonResponse;
use App\Traits\Emails;

class TransactionEmails extends Controller
{
    use Emails;

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

    public function create(Transaction $transaction): JsonResponse
    {
        $contacts = $transaction->contact->withPersons();

        $email_template = config('type.transaction.' . $transaction->type . '.email_template');

        if (request()->get('email_template')) {
            $email_template = request()->get('email_template');
        }

        switch ($email_template) {
            case 'invoice_payment_customer':
                $notification = new PaymentReceivedNotification($transaction->document, $transaction, $email_template, true);
                break;

            default:
                $notification = new TransactionNotification($transaction, $email_template, true);
                break;
        }

        $store_route = 'modals.transactions.emails.store';

        $html = view('modals.transactions.email', compact('transaction', 'contacts', 'notification', 'store_route'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('general.title.new', ['type' => trans_choice('general.email', 1)]),
                'buttons' => [
                    'cancel' => [
                        'text' => trans('general.cancel'),
                        'class' => 'btn-outline-secondary',
                    ],
                    'confirm' => [
                        'text' => trans('general.send'),
                        'class' => 'disabled:bg-green-100',
                    ]
                ]
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $transaction = Transaction::find($request->get('transaction_id'));

        $email_template = config('type.transaction.' . $transaction->type . '.email_template');

        $response = $this->sendEmail(new SendTransactionAsCustomMail($request, $email_template));

        if ($response['success']) {
            $route = config('type.transaction.' . $transaction->type . '.route.prefix');

            if ($alias = config('type.transaction.' . $transaction->type . '.alias')) {
                $route = $alias . '.' . $route;
            }

            $response['redirect'] = route($route . '.show', $transaction->id);

            $message = trans('documents.messages.email_sent', ['type' => trans_choice('general.transactions', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}

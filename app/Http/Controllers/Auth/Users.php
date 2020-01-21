<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\User as Request;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Traits\Uploads;
use Illuminate\Http\Request as BaseRequest;

class Users extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::with('roles')->collect();

        return view('auth.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $routes = [
            'dashboard' => trans_choice('general.dashboards', 1),
            'items.index' => trans_choice('general.items', 2),
            'invoices.index' => trans_choice('general.invoices', 2),
            'revenues.index' => trans_choice('general.revenues', 2),
            'customers.index' => trans_choice('general.customers', 2),
            'bills.index' => trans_choice('general.bills', 2),
            'payments.index' => trans_choice('general.payments', 2),
            'vendors.index' => trans_choice('general.vendors', 2),
            'accounts.index' => trans_choice('general.accounts', 2),
            'transfers.index' => trans_choice('general.transfers', 2),
            'transactions.index' => trans_choice('general.transactions', 2),
            'reconciliations.index' => trans_choice('general.reconciliations', 2),
            'reports.index' => trans_choice('general.reports', 2),
            'settings.index' => trans_choice('general.settings', 2),
            'categories.index' => trans_choice('general.categories', 2),
            'currencies.index' => trans_choice('general.currencies', 2),
            'taxes.index' => trans_choice('general.taxes', 2),
        ];

        $roles = Role::all()->reject(function ($r) {
            return $r->hasPermission('read-client-portal');
        });

        $companies = user()->companies()->get()->sortBy('name');

        return view('auth.users.create', compact('roles', 'companies', 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateUser($request));

        if ($response['success']) {
            $response['redirect'] = route('users.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.users', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('users.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function edit(User $user)
    {
        $routes = [
            'dashboard' => trans_choice('general.dashboards', 1),
            'items.index' => trans_choice('general.items', 2),
            'invoices.index' => trans_choice('general.invoices', 2),
            'revenues.index' => trans_choice('general.revenues', 2),
            'customers.index' => trans_choice('general.customers', 2),
            'bills.index' => trans_choice('general.bills', 2),
            'payments.index' => trans_choice('general.payments', 2),
            'vendors.index' => trans_choice('general.vendors', 2),
            'accounts.index' => trans_choice('general.accounts', 2),
            'transfers.index' => trans_choice('general.transfers', 2),
            'transactions.index' => trans_choice('general.transactions', 2),
            'reconciliations.index' => trans_choice('general.reconciliations', 2),
            'reports.index' => trans_choice('general.reports', 2),
            'settings.index' => trans_choice('general.settings', 2),
            'categories.index' => trans_choice('general.categories', 2),
            'currencies.index' => trans_choice('general.currencies', 2),
            'taxes.index' => trans_choice('general.taxes', 2),
        ];

        if ($user->can('read-client-portal')) {
            // Show only roles with customer permission
            $roles = Role::all()->reject(function ($r) {
                return !$r->hasPermission('read-client-portal');
            });
        } else {
            // Don't show roles with customer permission
            $roles = Role::all()->reject(function ($r) {
                return $r->hasPermission('read-client-portal');
            });
        }

        $companies = user()->companies()->get()->sortBy('name');

        return view('auth.users.edit', compact('user', 'companies', 'roles', 'routes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User $user
     * @param  Request $request
     *
     * @return Response
     */
    public function update(User $user, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateUser($user, $request));

        if ($response['success']) {
            $response['redirect'] = route('users.index');

            $message = trans('messages.success.updated', ['type' => $user->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('users.edit', $user->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  User $user
     *
     * @return Response
     */
    public function enable(User $user)
    {
        $response = $this->ajaxDispatch(new UpdateUser($user, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $user->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  User $user
     *
     * @return Response
     */
    public function disable(User $user)
    {
        $response = $this->ajaxDispatch(new UpdateUser($user, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $user->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        $response = $this->ajaxDispatch(new DeleteUser($user));

        $response['redirect'] = route('users.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $user->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Mark upcoming bills notifications are read and redirect to bills page.
     *
     * @param  User $user
     *
     * @return Response
     */
    public function readUpcomingBills(User $user)
    {
        // Mark bill notifications as read
        foreach ($user->unreadNotifications as $notification) {
            // Not a bill notification
            if ($notification->getAttribute('type') != 'App\Notifications\Purchase\Bill') {
                continue;
            }

            $notification->markAsRead();
        }

        return redirect()->route('bills.index');
    }

    /**
     * Mark overdue invoices notifications are read and redirect to invoices page.
     *
     * @param  User $user
     *
     * @return Response
     */
    public function readOverdueInvoices(User $user)
    {
        // Mark invoice notifications as read
        foreach ($user->unreadNotifications as $notification) {
            // Not an invoice notification
            if ($notification->getAttribute('type') != 'App\Notifications\Sale\Invoice') {
                continue;
            }

            $notification->markAsRead();
        }

        return redirect()->route('invoices.index');
    }

    public function autocomplete(BaseRequest $request)
    {
        $user = false;
        $data = false;

        $column = $request['column'];
        $value = $request['value'];

        if (!empty($column) && !empty($value)) {
            switch ($column) {
                case 'id':
                    $user = User::find((int) $value);
                    break;
                case 'email':
                    $user = User::where('email', $value)->first();
                    break;
                default:
                    $user = User::where($column, $value)->first();
            }

            $data = $user;
        } elseif (!empty($column) && empty($value)) {
            $data = trans('validation.required', ['attribute' => $column]);
        }

        return response()->json([
            'errors'  => ($user) ? false : true,
            'success' => ($user) ? true : false,
            'data'    => $data
        ]);
    }
}

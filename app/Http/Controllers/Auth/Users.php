<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Events\Auth\LandingPageShowing;
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

    public function __construct()
    {
        $this->middleware('permission:create-auth-users')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-auth-users')->only('index', 'show', 'export');
        $this->middleware('permission:update-auth-users')->only('enable', 'disable');
        $this->middleware('permission:delete-auth-users')->only('destroy');

        $this->middleware('permission:read-auth-users|read-auth-profile')->only('edit');
        $this->middleware('permission:update-auth-users|update-auth-profile')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::with('media', 'roles')->collect();

        return $this->response('auth.users.index', compact('users'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $u = new \stdClass();
        $u->landing_pages = [];

        event(new LandingPageShowing($u));

        $landing_pages = $u->landing_pages;

        $roles = Role::all()->reject(function ($r) {
            return $r->hasPermission('read-client-portal');
        });

        $companies = user()->companies()->take(setting('default.select_limit'))->get()->sortBy('name')->pluck('name', 'id');

        return view('auth.users.create', compact('roles', 'companies', 'landing_pages'));
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

            flash($message)->error()->important();
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
        if (user()->cannot('read-auth-users') && ($user->id != user()->id)) {
            abort(403);
        }

        $u = new \stdClass();
        $u->landing_pages = [];

        event(new LandingPageShowing($u));

        $landing_pages = $u->landing_pages;

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

        $companies = user()->companies()->take(setting('default.select_limit'))->get()->sortBy('name')->pluck('name', 'id');

        if ($user->company_ids) {
            foreach($user->company_ids as $company_id) {
                if ($companies->has($company_id)) {
                    continue;
                }

                $company = \App\Models\Common\Company::find($company_id);

                $companies->put($company->id, $company->name);
            }
        }

        return view('auth.users.edit', compact('user', 'companies', 'roles', 'landing_pages'));
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
        if (user()->cannot('update-auth-users') && ($user->id != user()->id)) {
            abort(403);
        }

        $response = $this->ajaxDispatch(new UpdateUser($user, $request));

        if ($response['success']) {
            $response['redirect'] = user()->can('read-auth-users') ? route('users.index') : route('users.edit', $user->id);

            $message = trans('messages.success.updated', ['type' => $user->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('users.edit', $user->id);

            $message = $response['message'];

            flash($message)->error()->important();
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

            flash($message)->error()->important();
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

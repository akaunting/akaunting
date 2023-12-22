<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Events\Auth\LandingPageShowing;
use App\Http\Requests\Auth\User as Request;
use App\Jobs\Auth\CreateInvitation;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;
use App\Traits\Cloud;
use App\Traits\Uploads;
use Illuminate\Http\Request as BaseRequest;

class Users extends Controller
{
    use Cloud, Uploads;

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
        $users = user_model_class()::with('companies', 'media', 'permissions', 'roles')->isNotCustomer()->collect();

        return $this->response('auth.users.index', compact('users'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show($user_id)
    {
        $user = user_model_class()::find($user_id);

        $u = new \stdClass();
        $u->role = $user->roles()->first();
        $u->landing_pages = [];

        event(new LandingPageShowing($u));

        $landing_pages = $u->landing_pages;

        $companies = $user->companies()->collect();

        return view('auth.users.show', compact('user', 'landing_pages', 'companies'));
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

        $roles = role_model_class()::all()->reject(function ($r) {
            $status = $r->hasPermission('read-client-portal');

            if ($r->name == 'employee') {
                $status = true;
            }

            return $status;
        })->pluck('display_name', 'id');

        $companies = user()->companies()->take(setting('default.select_limit'))->get()->sortBy('name')->pluck('name', 'id');

        $roles_url = $this->getCloudRolesPageUrl();

        return view('auth.users.create', compact('roles', 'companies', 'landing_pages', 'roles_url'));
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
            $response['redirect'] = route('users.show', $response['data']->id);

            $message = trans('messages.success.invited', ['type' => trans_choice('general.users', 1)]);

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
     * @param  $user_id
     *
     * @return Response
     */
    public function edit($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        if ((user()->cannot('read-auth-users') && ($user->id != user()->id)) || empty($user)) {
            abort(403);
        }

        $u = new \stdClass();
        $u->role = $user->roles()->first();
        $u->landing_pages = [];

        event(new LandingPageShowing($u));

        $landing_pages = $u->landing_pages;

        if ($user->isCustomer()) {
            // Show only roles with customer permission
            $roles = role_model_class()::all()->reject(function ($r) {
                return ! $r->hasPermission('read-client-portal');
            })->pluck('display_name', 'id');
        } else if ($user->isEmployee()) {
            // Show only roles with employee permission
            $roles = role_model_class()::where('name', 'employee')->get()->pluck('display_name', 'id');
        } else {
            // Don't show roles with customer permission
            $roles = role_model_class()::all()->reject(function ($r) {
                $status = $r->hasPermission('read-client-portal');

                if ($r->name == 'employee') {
                    $status = true;
                }

                return $status;
            })->pluck('display_name', 'id');
        }

        $companies = user()->companies()->take(setting('default.select_limit'))->get()->sortBy('name')->pluck('name', 'id');

        if ($user->company_ids) {
            foreach ($user->company_ids as $company_id) {
                if ($companies->has($company_id)) {
                    continue;
                }

                $company = company($company_id);

                $companies->put($company->id, $company->name);
            }
        }

        $roles_url = $this->getCloudRolesPageUrl();

        $route = (request()->route()->getName() == 'profile.edit') ? 'profile.update' : 'users.update';

        return view('auth.users.edit', compact('user', 'companies', 'roles', 'landing_pages', 'roles_url', 'route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $user_id
     * @param  Request $request
     *
     * @return Response
     */
    public function update($user_id, Request $request)
    {
        $user = user_model_class()::find($user_id);

        if ((user()->cannot('update-auth-users') && ($user->id != user()->id)) || empty($user)) {
            abort(403);
        }

        $response = $this->ajaxDispatch(new UpdateUser($user, $request));

        if ($response['success']) {
            $response['redirect'] = user()->can('read-auth-users') ? route('users.show', $user->id) : route('users.edit', $user->id);

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
     * @param  $user_id
     *
     * @return Response
     */
    public function enable($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        if (user()->cannot('update-auth-users') || empty($user)) {
            abort(403);
        }

        $response = $this->ajaxDispatch(new UpdateUser($user, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $user->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  $user_id
     *
     * @return Response
     */
    public function disable($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        if (user()->cannot('update-auth-users') || empty($user)) {
            abort(403);
        }

        $response = $this->ajaxDispatch(new UpdateUser($user, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $user->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $user_id
     *
     * @return Response
     */
    public function destroy($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        if (user()->cannot('delete-auth-users') || empty($user)) {
            abort(403);
        }

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
     * @param  $user_id
     *
     * @return Response
     */
    public function readUpcomingBills($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        if (user()->cannot('read-auth-users') || empty($user)) {
            abort(403);
        }

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
     * @param  $user_id
     *
     * @return Response
     */
    public function readOverdueInvoices($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);

        if (user()->cannot('read-auth-users') || empty($user)) {
            abort(403);
        }

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

        if (! empty($column) && ! empty($value)) {
            switch ($column) {
                case 'id':
                    $user = user_model_class()::find((int) $value);
                    break;
                case 'email':
                    $user = user_model_class()::where('email', $value)->first();
                    break;
                default:
                    $user = user_model_class()::where($column, $value)->first();
            }

            $data = $user;
        } elseif (! empty($column) && empty($value)) {
            $data = trans('validation.required', ['attribute' => $column]);
        }

        return response()->json([
            'errors'  => ($user) ? false : true,
            'success' => ($user) ? true : false,
            'data'    => $data,
        ]);
    }

    /**
     * Process request for reinviting the specified resource.
     *
     * @param  $user_id
     *
     * @return Response
     */
    public function invite($user_id)
    {
        $user = user_model_class()::find($user_id);

        $response = $this->ajaxDispatch(new CreateInvitation($user, company()));

        if ($response['success']) {
            $message = trans('messages.success.invited', ['type' => trans_choice('general.users', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return redirect()->route('users.index');
    }

    /**
     * Process request for reinviting the specified resource.
     *
     * @param  role_model_class()  $role
     *
     * @return Response
     */
    public function landingPages(BaseRequest $request)
    {
        $role = false;

        if ($request->has('role_id')) {
            $role = role_model_class()::find($request->get('role_id'));
        }

        $u = new \stdClass();
        $u->role = $role;
        $u->landing_pages = [];

        event(new LandingPageShowing($u));

        $landing_pages = $u->landing_pages;

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $landing_pages,
            'message' => 'Get role by landing pages..',
        ]);
    }
}

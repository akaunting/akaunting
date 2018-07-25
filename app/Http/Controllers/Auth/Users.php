<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User as Request;
use Illuminate\Http\Request as ARequest;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Traits\Uploads;

use Auth;

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

        $roles = collect(Role::all()->pluck('display_name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.roles', 2)]), '');

        return view('auth.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::all()->reject(function ($r) {
            return $r->hasPermission('read-customer-panel');
        });

        $companies = Auth::user()->companies()->get()->sortBy('name');

        foreach ($companies as $company) {
            $company->setSettings();
        }

        return view('auth.users.create', compact('roles', 'companies'));
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
        // Create user
        $user = User::create($request->input());

        // Upload picture
        if ($request->file('picture')) {
            $media = $this->getMedia($request->file('picture'), 'users');

            $user->attachMedia($media, 'picture');
        }

        // Attach roles
        $user->roles()->attach($request['roles']);

        // Attach companies
        $user->companies()->attach($request['companies']);

        $message = trans('messages.success.added', ['type' => trans_choice('general.users', 1)]);

        flash($message)->success();

        return redirect('auth/users');
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
        if ($user->customer) {
            // Show only roles with customer permission
            $roles = Role::all()->reject(function ($r) {
                return !$r->hasPermission('read-customer-panel');
            });
        } else {
            // Don't show roles with customer permission
            $roles = Role::all()->reject(function ($r) {
                return $r->hasPermission('read-customer-panel');
            });
        }

        $companies = Auth::user()->companies()->get()->sortBy('name');

        foreach ($companies as $company) {
            $company->setSettings();
        }

        return view('auth.users.edit', compact('user', 'companies', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User  $user
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(User $user, Request $request)
    {
        // Do not reset password if not entered/changed
        if (empty($request['password'])) {
            unset($request['password']);
            unset($request['password_confirmation']);
        }

        // Update user
        $user->update($request->input());

        // Upload picture
        if ($request->file('picture')) {
            $media = $this->getMedia($request->file('picture'), 'users');

            $user->attachMedia($media, 'picture');
        }

        // Sync roles
        $user->roles()->sync($request['roles']);

        // Sync companies
        $user->companies()->sync($request['companies']);

        $message = trans('messages.success.updated', ['type' => trans_choice('general.users', 1)]);

        flash($message)->success();

        return redirect('auth/users');
    }

    /**
     * Enable the specified resource.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function enable(User $user)
    {
        $user->enabled = 1;
        $user->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('general.users', 1)]);

        flash($message)->success();

        return redirect()->route('users.index');
    }

    /**
     * Disable the specified resource.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function disable(User $user)
    {
        $user->enabled = 0;
        $user->save();

        $message = trans('messages.success.disabled', ['type' => trans_choice('general.users', 1)]);

        flash($message)->success();

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        // Can't delete yourself
        if ($user->id == \Auth::user()->id) {
            $message = trans('auth.error.self_delete');

            flash($message)->error();

            return redirect('auth/users');
        }

        $user->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.users', 1)]);

        flash($message)->success();

        return redirect('auth/users');
    }

    /**
     * Mark upcoming bills notifications are read and redirect to bills page.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function readUpcomingBills(User $user)
    {
        // Mark bill notifications as read
        foreach ($user->unreadNotifications as $notification) {
            // Not a bill notification
            if ($notification->getAttribute('type') != 'App\Notifications\Expense\Bill') {
                continue;
            }

            $notification->markAsRead();
        }

        // Redirect to bills
        return redirect('expenses/bills');
    }

    /**
     * Mark overdue invoices notifications are read and redirect to invoices page.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function readOverdueInvoices(User $user)
    {
        // Mark invoice notifications as read
        foreach ($user->unreadNotifications as $notification) {
            // Not an invoice notification
            if ($notification->getAttribute('type') != 'App\Notifications\Income\Invoice') {
                continue;
            }

            $notification->markAsRead();
        }

        // Redirect to invoices
        return redirect('incomes/invoices');
    }

    /**
     * Mark items out of stock notifications are read and redirect to items page.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function readItemsOutOfStock(User $user)
    {
        // Mark item notifications as read
        foreach ($user->unreadNotifications as $notification) {
            // Not an item notification
            if ($notification->getAttribute('type') != 'App\Notifications\Common\Item') {
                continue;
            }

            $notification->markAsRead();
        }

        // Redirect to items
        return redirect('common/items');
    }

    public function autocomplete(ARequest $request)
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

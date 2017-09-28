<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User as Request;
use App\Models\Auth\User;
use App\Models\Auth\Role;

use Auth;

class Users extends Controller
{
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
        $roles = Role::all();

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
        // Upload picture
        $picture = $request->file('picture');
        if ($picture && $picture->isValid()) {
            $request['picture'] = $picture->store('uploads/users');
        }
        
        // Create user
        $user = User::create($request->input());

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
        $roles = Role::all();

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
        // Upload picture
        $picture = $request->file('picture');
        if ($picture && $picture->isValid()) {
            $request['picture'] = $picture->store('users');
        }

        // Do not reset password if not entered/changed
        if (empty($request['password'])) {
            unset($request['password']);
            unset($request['password_confirmation']);
        }

        // Update user
        $user->update($request->input());

        // Sync roles
        $user->roles()->sync($request['roles']);

        // Sync companies
        $user->companies()->sync($request['companies']);

        $message = trans('messages.success.updated', ['type' => trans_choice('general.users', 1)]);

        flash($message)->success();

        return redirect('auth/users');
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
}

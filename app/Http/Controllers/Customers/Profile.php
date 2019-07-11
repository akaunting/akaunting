<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Profile as Request;
use App\Models\Auth\User;
use App\Traits\Uploads;

class Profile extends Controller
{
    use Uploads;

    public function index()
    {
        return $this->edit();
    }

    public function show()
    {
        return $this->edit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $user = auth()->user();

        return view('customers.profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

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

        // Update customer
        $user->customer->update($request->input());

        $message = trans('messages.success.updated', ['type' => trans('auth.profile')]);

        flash($message)->success();

        return redirect('customers/profile/edit');
    }

    /**
     * Mark overdue invoices notifications are read and redirect to invoices page.
     *
     * @return Response
     */
    public function readOverdueInvoices()
    {
        $user = auth()->user();

        // Mark invoice notifications as read
        foreach ($user->unreadNotifications as $notification) {
            // Not an invoice notification
            if ($notification->getAttribute('type') != 'App\Notifications\Income\Invoice') {
                continue;
            }

            $notification->markAsRead();
        }

        // Redirect to invoices
        return redirect('customers/invoices');
    }
}

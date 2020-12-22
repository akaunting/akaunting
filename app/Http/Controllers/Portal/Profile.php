<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Portal\Profile as Request;
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
        $user = user();

        return view('portal.profile.edit', compact('user'));
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
        $user = user();

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
        $user->contact->update($request->input());

        $message = trans('messages.success.updated', ['type' => trans('auth.profile')]);

        flash($message)->success();

        $response = [
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => '',
            'redirect' => route('portal.profile.edit', $user->id),
        ];

        return response()->json($response);
    }

    /**
     * Mark overdue invoices notifications are read and redirect to invoices page.
     *
     * @return Response
     */
    public function readOverdueInvoices()
    {
        $user = user();

        // Mark invoice notifications as read
        foreach ($user->unreadNotifications as $notification) {
            // Not an invoice notification
            if ($notification->getAttribute('type') != 'App\Notifications\Sale\Invoice') {
                continue;
            }

            $notification->markAsRead();
        }

        // Redirect to invoices
        return redirect()->route('portal.invoices.index');
    }
}

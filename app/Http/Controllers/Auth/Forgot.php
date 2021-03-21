<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class Forgot extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Where to redirect users after reset.
     *
     * @var string
     */
    protected $redirectTo = 'auth/forgot';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.forgot.create');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse($response)
    {
        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => trans('passwords.sent'),
            'data' => null,
            'redirect' => null,
        ];

        return response()->json($response);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse($response)
    {
        $response = [
            'status' => null,
            'success' => false,
            'error' => true,
            'message' => trans('passwords.user'),
            'data' => null,
            'redirect' => null,
        ];

        return response()->json($response);
    }
}

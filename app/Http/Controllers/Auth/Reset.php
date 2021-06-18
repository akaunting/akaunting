<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class Reset extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    public $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create(Request $request, $token = null)
    {
        return view('auth.reset.create')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => $password,
            'remember_token' => Str::random(60),
        ])->save();

        $this->guard()->login($user);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse($response)
    {
        $user = user();

        $company = $user::withoutEvents(function () use ($user) {
            return $user->companies()->enabled()->first();
        });

        // Logout if no company assigned
        if (!$company) {
            $this->guard()->logout();

            return response()->json([
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('auth.error.no_company'),
                'data' => null,
                'redirect' => null,
            ]);
        }

        // Redirect to portal if is customer
        if ($user->can('read-client-portal')) {
            $this->redirectTo = route('portal.dashboard', ['company_id' => $company->id]);
        }

        return response()->json([
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => null,
            'data' => null,
            'redirect' => url($this->redirectTo),
        ]);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'status' => null,
            'success' => false,
            'error' => true,
            'message' => trans($response),
            'data' => null,
            'redirect' => null,
        ]);
    }
}

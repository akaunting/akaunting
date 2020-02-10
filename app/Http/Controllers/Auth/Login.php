<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Login as Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;

class Login extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }

    public function create()
    {
        return view('auth.login.create');
    }

    public function store(Request $request)
    {
        // Attempt to login
        if (!auth()->attempt($request->only('email', 'password'), $request->get('remember', false))) {
            $response = [
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('auth.failed'),
                'data' => null,
                'redirect' => null,
            ];

            return response()->json($response);
        }

        // Get user object
        $user = user();

        // Check if user is enabled
        if (!$user->enabled) {
            $this->logout();

            $response = [
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('auth.disabled'),
                'data' => null,
                'redirect' => null,
            ];

            return response()->json($response);
        }

        // Check if is customer
        if ($user->can('read-client-portal')) {
            $path = session('url.intended', 'portal');

            // Path must start with 'portal' prefix
            if (!Str::startsWith($path, 'portal')) {
                $path = 'portal';
            }

            $response = [
                'status' => null,
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => null,
                'redirect' => url($path),
            ];

            return response()->json($response);
        }

        session(['dashboard_id' => $user->dashboards()->enabled()->pluck('id')->first()]);

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => null,
            'data' => null,
            'redirect' => redirect()->intended(route($user->landing_page))->getTargetUrl(),
        ];

        return response()->json($response);
    }

    public function destroy()
    {
        $this->logout();

        return redirect()->route('login');
    }

    public function logout()
    {
        auth()->logout();

        // Session destroy is required if stored in database
        if (env('SESSION_DRIVER') == 'database') {
            $request = app('Illuminate\Http\Request');
            $request->session()->getHandler()->destroy($request->session()->getId());
        }
    }
}

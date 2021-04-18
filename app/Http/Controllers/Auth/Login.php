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
            return response()->json([
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('auth.failed'),
                'data' => null,
                'redirect' => null,
            ]);
        }

        // Get user object
        $user = user();

        // Check if user is enabled
        if (!$user->enabled) {
            $this->logout();

            return response()->json([
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('auth.disabled'),
                'data' => null,
                'redirect' => null,
            ]);
        }

        $company = $user->withoutEvents(function () use ($user) {
            return $user->companies()->enabled()->first();
        });

        // Logout if no company assigned
        if (!$company) {
            $this->logout();

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
            $path = session('url.intended', '');

            // Path must start with company id and 'portal' prefix
            if (!Str::startsWith($path, $company->id . '/portal')) {
                $path = route('portal.dashboard', ['company_id' => $company->id]);
            }

            return response()->json([
                'status' => null,
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => null,
                'redirect' => url($path),
            ]);
        }

        // Redirect to landing page if is user
        $url = route($user->landing_page, ['company_id' => $company->id]);

        return response()->json([
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => null,
            'data' => null,
            'redirect' => redirect()->intended($url)->getTargetUrl(),
        ]);
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
        if (config('session.driver') == 'database') {
            $request = app('Illuminate\Http\Request');
            $request->session()->getHandler()->destroy($request->session()->getId());
        }
    }
}

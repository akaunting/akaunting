<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Login as Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
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
        // Only the failed logins count against the login limit, so it is applied here
        // and not as middleware, which would count every login and answer with a 429
        // the login form does not show
        $limit = RateLimiter::limiter('login')($request);
        $throttle_key = 'login|' . $limit->key;

        if (RateLimiter::tooManyAttempts($throttle_key, $limit->maxAttempts)) {
            return $this->respondLoginFailed(trans('auth.throttle', ['seconds' => RateLimiter::availableIn($throttle_key)]));
        }

        // Attempt to login
        if (! auth()->attempt($request->only('email', 'password'), $request->get('remember', false))) {
            RateLimiter::hit($throttle_key, $limit->decayMinutes * 60);

            return $this->respondLoginFailed();
        }

        RateLimiter::clear($throttle_key);

        // Get user object
        $user = user();

        // Check if user is enabled
        if (! $user->enabled) {
            $this->logout();

            // Security (CWE-204): avoid distinct error messages that would
            // allow valid email enumeration. Log the real reason server-side
            // for administrators/auditing without leaking it to the client.
            Log::info('Login denied: account disabled', [
                'email' => $request->email,
            ]);

            return $this->respondLoginFailed();
        }

        $company = $user->withoutEvents(function () use ($user) {
            return $user->companies()->enabled()->first();
        });

        // Logout if no company assigned
        if (! $company) {
            $this->logout();

            // Security (CWE-204): do not expose "no company" message to the
            // client; log it server-side instead.
            Log::info('Login denied: no company assigned', [
                'email' => $request->email,
            ]);

            return $this->respondLoginFailed();
        }

        // Redirect to portal if is customer
        if ($user->isCustomer()) {
            $path = session('url.intended', '');

            // Path must start with company id and 'portal' prefix
            if (!Str::startsWith($path, $company->id . '/portal')) {
                $path = route('portal.dashboard', ['company_id' => $company->id]);
            }

            return response()->json([
                'status' => null,
                'success' => true,
                'error' => false,
                'message' => trans('auth.login_redirect'),
                'data' => null,
                'redirect' => url($path),
            ]);
        }

        $route_name = $user->landing_page;

        // Module routes are not registered on this guest request, and the landing page
        // can point to a module, so the always available dashboard is used as a fallback.
        if (! Route::has($route_name)) {
            $route_name = 'dashboard';
        }

        // Redirect to landing page if is user
        $url = route($route_name, ['company_id' => $company->id]);

        return response()->json([
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => trans('auth.login_redirect'),
            'data' => null,
            'redirect' => redirect()->intended($url)->getTargetUrl(),
        ]);
    }

    /**
     * Build the generic failed-login JSON response.
     *
     * Security (CWE-204): every rejected login returns the same message and
     * response shape so an unauthenticated attacker cannot distinguish
     * "email does not exist" from "disabled" or "no company" and enumerate
     * valid registered email addresses.
     */
    protected function respondLoginFailed(?string $message = null)
    {
        return response()->json([
            'status' => null,
            'success' => false,
            'error' => true,
            'message' => $message ?? trans('auth.failed'),
            'data' => null,
            'redirect' => null,
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

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->getHandler()->destroy($request->session()->getId());
        }
    }
}

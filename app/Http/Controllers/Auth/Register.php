<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Register as Request;
use App\Jobs\Auth\DeleteInvitation;
use App\Models\Auth\UserInvitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

class Register extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function create($token)
    {
        $invitation = UserInvitation::token($token)->first();

        if ($invitation) {
            return view('auth.register.create', ['token' => $token]);
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $invitation = UserInvitation::token($request->get('token'))->first();

        if (!$invitation) {
            abort(403);
        }

        $user = $invitation->user;

        $this->dispatch(new DeleteInvitation($invitation));

        event(new Registered($user));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $user->forceFill([
            'password' => $request->password,
            'remember_token' => Str::random(60),
        ])->save();

        $this->guard()->login($user);

        $message = trans('messages.success.connected', ['type' => trans_choice('general.users', 1)]);

        flash($message)->success();

        return response()->json([
            'redirect' => url($this->redirectPath()),
        ]);
    }
}

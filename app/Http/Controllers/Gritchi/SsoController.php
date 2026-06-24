<?php

namespace App\Http\Controllers\Gritchi;

use App\Abstracts\Http\Controller;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Services\Gritchi\SsoToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class SsoController extends Controller
{
    public function consume(Request $request)
    {
        try {
            $payload = SsoToken::decode((string) $request->query('token'));
            $company = Company::enabled()->first() ?: Company::first();

            if (! $company) {
                throw new RuntimeException('No Akaunting company exists yet.');
            }

            $user = DB::transaction(function () use ($payload, $company) {
                $user = User::withTrashed()->firstOrNew(['email' => strtolower($payload['email'])]);

                $user->fill([
                    'name' => $this->nameFromPayload($payload),
                    'email' => strtolower($payload['email']),
                    'password' => Str::random(40),
                    'locale' => 'en-GB',
                    'enabled' => true,
                    'landing_page' => 'dashboard',
                ]);

                if ($user->trashed()) {
                    $user->restore();
                }

                $user->save();

                $user->companies()->syncWithoutDetaching([$company->id]);

                if ($role = Role::first()) {
                    $user->roles()->syncWithoutDetaching([$role->id]);
                }

                Artisan::call('user:seed', [
                    'user' => $user->id,
                    'company' => $company->id,
                ]);

                return $user;
            });

            auth()->login($user);
            $request->session()->regenerate();

            return redirect()->route($user->landing_page, ['company_id' => $company->id]);
        } catch (\Throwable $error) {
            report($error);

            return redirect()->route('login')->withErrors([
                'email' => 'Could not sign in from Gritchi Portal.',
            ]);
        }
    }

    private function nameFromPayload(array $payload): string
    {
        return trim(implode(' ', array_filter([
            $payload['first_name'] ?? null,
            $payload['last_name'] ?? null,
        ]))) ?: $payload['email'];
    }
}

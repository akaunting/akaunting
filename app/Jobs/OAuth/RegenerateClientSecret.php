<?php

namespace App\Jobs\OAuth;

use App\Abstracts\Job;
use App\Events\OAuth\ClientSecretRegenerated;
use App\Interfaces\Job\ShouldUpdate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegenerateClientSecret extends Job implements ShouldUpdate
{
    public function handle()
    {
        $plainSecret = Str::random(40);

        DB::transaction(function () use ($plainSecret) {
            $this->model->secret = config('oauth.hash_client_secrets', false)
                ? Hash::make($plainSecret)
                : $plainSecret;
            $this->model->save();
        });

        event(new ClientSecretRegenerated($this->model, $plainSecret));

        // Store plain secret temporarily so the controller can return it
        $this->model->plain_secret = $plainSecret;

        return $this->model;
    }
}

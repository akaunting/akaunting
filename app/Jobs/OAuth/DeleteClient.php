<?php

namespace App\Jobs\OAuth;

use App\Abstracts\Job;
use App\Events\OAuth\ClientDeleted;
use App\Interfaces\Job\ShouldDelete;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;

class DeleteClient extends Job implements ShouldDelete
{
    public function handle()
    {
        /** @var ClientRepository $repository */
        $repository = app(ClientRepository::class);

        DB::transaction(function () use ($repository) {
            $repository->delete($this->model);
        });

        event(new ClientDeleted($this->model));

        return $this->model;
    }
}

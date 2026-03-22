<?php

namespace App\Jobs\OAuth;

use App\Abstracts\Job;
use App\Events\OAuth\ClientUpdated;
use App\Interfaces\Job\ShouldUpdate;
use Laravel\Passport\ClientRepository;

class UpdateClient extends Job implements ShouldUpdate
{
    public function handle()
    {
        $name     = $this->request->get('name');
        $redirect = $this->request->get('redirect');
        $original = $this->model->getOriginal();

        /** @var ClientRepository $repository */
        $repository = app(ClientRepository::class);

        \DB::transaction(function () use ($repository, $name, $redirect) {
            $repository->update($this->model, $name, $redirect);
        });

        event(new ClientUpdated($this->model->fresh(), $original));

        return $this->model->fresh();
    }
}

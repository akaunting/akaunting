<?php

namespace App\Jobs\OAuth;

use App\Abstracts\Job;
use App\Events\OAuth\ClientCreated;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\OAuth\Client;
use Laravel\Passport\ClientRepository;

class CreateClient extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle()
    {
        $name       = $this->request->get('name');
        $redirect   = $this->request->get('redirect');
        $confidential = $this->request->filled('confidential');

        /** @var ClientRepository $repository */
        $repository = app(ClientRepository::class);

        \DB::transaction(function () use ($repository, $name, $redirect, $confidential) {
            $this->model = $repository->create(
                user_id(),
                $name,
                $redirect,
                null,   // provider
                false,  // personal access client
                false,  // password client
                ! $confidential  // public = no secret
            );

            $this->model->company_id  = company_id();
            $this->model->created_from = $this->request->get('created_from', 'oauth.web');
            $this->model->created_by  = user_id();
            $this->model->save();
        });

        event(new ClientCreated($this->model, ! $confidential));

        return $this->model;
    }
}

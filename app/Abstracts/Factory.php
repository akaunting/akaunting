<?php

namespace App\Abstracts;

use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Traits\Jobs;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Factory extends BaseFactory
{
    use Jobs;

    /**
     * @var Company
     */
    protected $company;

    /**
     * @var User|EloquentModel|object|null
     */
    protected $user;

    public function __construct(...$arguments)
    {
        parent::__construct(...$arguments);

        config(['mail.default' => 'array']);

        $this->user = User::first();
        $this->company = $this->user->companies()->first();

        company($this->company->id)->makeCurrent();

        app('url')->defaults(['company_id' => company_id()]);
    }

    public function getCompanyUsers()
    {
        return $this->company->users()->enabled()->get()->pluck('id')->toArray();
    }
}

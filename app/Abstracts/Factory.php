<?php

namespace App\Abstracts;

use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Traits\Jobs;
use Closure;
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

        // TODO: this location was put to make US | for "gmail.co.uk" issue
        $this->faker = \Faker\Factory::create();

        $this->setUser();

        $this->setCompany();
    }

    public function getCompanyUsers(): array
    {
        return $this->company->users()->enabled()->get()->pluck('id')->toArray();
    }

    public function company(int $id): static
    {
        return $this->state([
            'company_id' => $id,
        ]);
    }

    public function setUser(): void
    {
        $this->user = User::first();
    }

    public function setCompany(): void
    {
        $company_id = $this->getRawAttribute('company_id');

        $this->company = !empty($company_id) ? Company::find($company_id) : $this->user->companies()->first();

        $this->company->makeCurrent();

        app('url')->defaults(['company_id' => company_id()]);
    }

    public function getRawAttribute($key)
    {
        $raw = $this->state([])->getExpandedAttributes(null);

        return $raw[$key] ?? null;
    }
}

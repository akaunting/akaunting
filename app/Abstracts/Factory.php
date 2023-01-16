<?php

namespace App\Abstracts;

use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Traits\Jobs;
use App\Utilities\Date;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Cache;

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
        Cache::put('state_company_id', $id, Date::now()->addHour(6));

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
        $state_id = Cache::get('state_company_id');

        $this->company = ! is_null($state_id) ? company($state_id) : $this->user->companies()->first();

        $this->company->makeCurrent();

        //app('url')->defaults(['company_id' => $this->company->id]);
    }

    public function getRawAttribute($key)
    {
        $raw = $this->state([])->getExpandedAttributes(null);

        return $raw[$key] ?? null;
    }
}

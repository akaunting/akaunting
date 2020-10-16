<?php

namespace App\Abstracts;

use App\Models\Auth\User;
use App\Traits\Jobs;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

abstract class Factory extends BaseFactory
{
    use Jobs;

    public function __construct(...$arguments)
    {
        parent::__construct(...$arguments);

        $this->user = User::first();
        $this->company = $this->user->companies()->first();

        session(['company_id' => $this->company->id]);
        setting()->setExtraColumns(['company_id' => $this->company->id]);
    }

    public function getCompanyUsers()
    {
        return $this->company->users()->enabled()->get()->pluck('id')->toArray();
    }
}

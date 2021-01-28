<?php

namespace App\Traits;

use App\Scopes\Company;

trait Tenants
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootTenants()
    {
        static::addGlobalScope(new Company);
    }

    public function isTenantable()
    {
        return (isset($this->tenantable) && ($this->tenantable === true));
    }

    public function isNotTenantable()
    {
        return !$this->isTenantable();
    }
}

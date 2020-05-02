<?php

namespace App\Traits;

trait Tenants
{
    public function isTenantable()
    {
        return (isset($this->tenantable) && ($this->tenantable === true));
    }

    public function isNotTenantable()
    {
        return !$this->isTenantable();
    }
}

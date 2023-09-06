<?php

namespace Laratrust\Contracts;

interface Ownable
{
    /**
     * Gets the owner key value inside the model or object.
     *
     * @param  mixed  $owner
     * @return mixed
     */
    public function ownerKey($owner);
}

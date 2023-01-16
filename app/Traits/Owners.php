<?php

namespace App\Traits;

trait Owners
{
    public function isOwnable()
    {
        $ownable = $this->ownable ?: true;

        return ($ownable === true) && in_array('created_by', $this->getFillable());
    }

    public function isNotOwnable()
    {
        return !$this->isOwnable();
    }
}

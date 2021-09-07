<?php

namespace App\Traits;

trait Sources
{
    public function isSourcable(): bool
    {
        $sourcable = $this->sourcable ?: true;

        return ($sourcable === true) && in_array('created_from', $this->getFillable());
    }

    public function isNotSourcable(): bool
    {
        return ! $this->isSourcable();
    }

    public function getSourceName($request = null): string
    {
        if (app()->runningInConsole()) {
            $source = 'console';
        }

        if (empty($source)) {
            $request = $request ?: request();

            $source = $request->isApi() ? 'api' : null;
        }

        if (empty($source)) {
            $source = 'ui';
        }

        return $source;
    }
}

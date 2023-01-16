<?php

namespace App\Traits;

use App\Utilities\QueueCollection;
use Illuminate\Support\Str;

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

    public function getSourceName($request = null, $alias = null): string
    {
        $prefix = $this->getSourcePrefix($alias);

        if (app()->runningInConsole()) {
            $source = $prefix . 'console';
        }

        if (empty($source)) {
            $request = $request ?: request();

            if ($request instanceof QueueCollection || running_in_queue()) {
                $source = $prefix . 'queue';
            } else {
                $source = $request->isApi() ? $prefix . 'api' : null;
            }
        }

        if (empty($source)) {
            $source = $prefix . 'ui';
        }

        return $source;
    }

    public function getSourcePrefix($alias = null)
    {
        $alias = is_null($alias) ? $this->getSourceAlias() : $alias;

        return $alias . '::';
    }

    public function getSourceAlias()
    {
        $prefix = '';

        $namespaces = explode('\\', get_class($this));

        if (empty($namespaces[0]) || (empty($namespaces[1]))) {
            return $prefix;
        }

        if ($namespaces[0] != 'Modules') {
            return 'core';
        }

        $prefix = Str::kebab($namespaces[1]);

        return $prefix;
    }
}

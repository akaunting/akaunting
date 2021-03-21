<?php namespace GeneaLabs\LaravelModelCaching\Traits;

use Illuminate\Container\Container;

trait CachePrefixing
{
    protected function getCachePrefix() : string
    {
        $cachePrefix = "genealabs:laravel-model-caching:";
        $useDatabaseKeying = Container::getInstance()
            ->make("config")
            ->get("laravel-model-caching.use-database-keying");

        if ($useDatabaseKeying) {
            $cachePrefix .= $this->getConnectionName() . ":";
            $cachePrefix .= $this->getDatabaseName() . ":";
        }

        $cachePrefix .= Container::getInstance()
            ->make("config")
            ->get("laravel-model-caching.cache-prefix", "");

        if ($this->model
            && property_exists($this->model, "cachePrefix")
        ) {
            $cachePrefix .= $this->model->cachePrefix . ":";
        }

        return $cachePrefix;
    }

    protected function getDatabaseName() : string
    {
        return $this->model->getConnection()->getDatabaseName();
    }

    protected function getConnectionName() : string
    {
        return $this->model->getConnection()->getName();
    }
}

<?php

namespace GeneaLabs\LaravelModelCaching\Traits;

use Illuminate\Database\Eloquent\Collection;

trait BuilderCaching
{
    public function all($columns = ['*']) : Collection
    {
        if (! $this->isCachable()) {
            $this->model->disableModelCaching();
        }

        return $this->model->get($columns);
    }

    public function truncate()
    {
        if ($this->isCachable()) {
            $this->model->flushCache();
        }

        return parent::truncate();
    }

    public function withoutGlobalScope($scope)
    {
        array_push($this->withoutGlobalScopes, $scope);

        return parent::withoutGlobalScope($scope);
    }

    public function withoutGlobalScopes(array $scopes = null)
    {
        if ($scopes !== null) {
            $this->withoutGlobalScopes = $scopes;
        }

        if (
            $scopes == null
            || (
                $scopes !== null
                && count($scopes) === 0
            )
        ) {
            $this->withoutAllGlobalScopes = true;
        }

        return parent::withoutGlobalScopes($scopes);
    }
}

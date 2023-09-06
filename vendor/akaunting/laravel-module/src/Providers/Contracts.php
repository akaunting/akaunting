<?php

namespace Akaunting\Module\Providers;

use Akaunting\Module\Contracts\RepositoryInterface;
use Akaunting\Module\Laravel\LaravelFileRepository;
use Illuminate\Support\ServiceProvider;

class Contracts extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, LaravelFileRepository::class);
    }
}

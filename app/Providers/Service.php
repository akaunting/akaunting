<?php

namespace App\Providers;

use App\Interfaces\Service\DocumentNumberService;
use App\Services\Document\CoreDocumentNumberService;
use Illuminate\Support\ServiceProvider;

class Service extends ServiceProvider
{
    /**
     * All container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        DocumentNumberService::class => CoreDocumentNumberService::class,
    ];
}

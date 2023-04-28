<?php

namespace App\Providers;

use App\Interfaces\Utility\DocumentNumber as DocumentNumberInterface;
use App\Utilities\DocumentNumber;
use Illuminate\Support\ServiceProvider;

class Binding extends ServiceProvider
{
    /**
     * All container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        DocumentNumberInterface::class => DocumentNumber::class,
    ];
}

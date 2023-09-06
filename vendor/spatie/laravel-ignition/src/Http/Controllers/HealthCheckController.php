<?php

namespace Spatie\LaravelIgnition\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class HealthCheckController
{
    public function __invoke()
    {
        return [
            'can_execute_commands' => $this->canExecuteCommands(),
        ];
    }

    protected function canExecuteCommands(): bool
    {
        Artisan::call('help', ['--version']);

        $output = Artisan::output();

        return Str::contains($output, app()->version());
    }
}

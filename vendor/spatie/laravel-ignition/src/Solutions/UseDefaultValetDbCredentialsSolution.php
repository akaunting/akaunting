<?php

namespace Spatie\LaravelIgnition\Solutions;

use Illuminate\Support\Str;
use Spatie\Ignition\Contracts\RunnableSolution;

class UseDefaultValetDbCredentialsSolution implements RunnableSolution
{
    public function getSolutionActionDescription(): string
    {
        return 'Pressing the button will change `DB_USER` and `DB_PASSWORD` in your `.env` file.';
    }

    public function getRunButtonText(): string
    {
        return 'Use default Valet credentials';
    }

    public function getSolutionTitle(): string
    {
        return 'Could not connect to database';
    }

    public function run(array $parameters = []): void
    {
        if (! file_exists(base_path('.env'))) {
            return;
        }

        $this->ensureLineExists('DB_USERNAME', 'root');
        $this->ensureLineExists('DB_PASSWORD', '');
    }

    protected function ensureLineExists(string $key, string $value): void
    {
        $envPath = base_path('.env');

        $envLines = array_map(fn (string $envLine) => Str::startsWith($envLine, $key)
            ? "{$key}={$value}".PHP_EOL
            : $envLine, file($envPath) ?: []);

        file_put_contents($envPath, implode('', $envLines));
    }

    public function getRunParameters(): array
    {
        return [];
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Valet documentation' => 'https://laravel.com/docs/master/valet',
        ];
    }

    public function getSolutionDescription(): string
    {
        return 'You seem to be using Valet, but the .env file does not contain the right default database credentials.';
    }
}

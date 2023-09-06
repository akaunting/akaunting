<?php

namespace Spatie\LaravelIgnition\Solutions;

use Livewire\LivewireComponentsFinder;
use Spatie\Ignition\Contracts\RunnableSolution;

class LivewireDiscoverSolution implements RunnableSolution
{
    protected string $customTitle;

    public function __construct(string $customTitle = '')
    {
        $this->customTitle = $customTitle;
    }

    public function getSolutionTitle(): string
    {
        return $this->customTitle;
    }

    public function getSolutionDescription(): string
    {
        return 'You might have forgotten to discover your Livewire components.';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Livewire: Artisan Commands' => 'https://laravel-livewire.com/docs/2.x/artisan-commands',
        ];
    }

    public function getRunParameters(): array
    {
        return [];
    }

    public function getSolutionActionDescription(): string
    {
        return 'You can discover your Livewire components using `php artisan livewire:discover`.';
    }

    public function getRunButtonText(): string
    {
        return 'Run livewire:discover';
    }

    public function run(array $parameters = []): void
    {
        app(LivewireComponentsFinder::class)->build();
    }
}

<?php

namespace Spatie\LaravelIgnition\Renderers;

use Spatie\FlareClient\Flare;
use Spatie\Ignition\Config\IgnitionConfig;
use Spatie\Ignition\Contracts\SolutionProviderRepository;
use Spatie\Ignition\Ignition;
use Spatie\LaravelIgnition\ContextProviders\LaravelContextProviderDetector;
use Spatie\LaravelIgnition\Solutions\SolutionTransformers\LaravelSolutionTransformer;
use Spatie\LaravelIgnition\Support\LaravelDocumentationLinkFinder;
use Throwable;

class ErrorPageRenderer
{
    public function render(Throwable $throwable): void
    {
        $viteJsAutoRefresh = '';

        if (class_exists('Illuminate\Foundation\Vite')) {
            $vite = app(\Illuminate\Foundation\Vite::class);

            if (is_file($vite->hotFile())) {
                $viteJsAutoRefresh = $vite->__invoke([]);
            }
        }

        app(Ignition::class)
            ->resolveDocumentationLink(
                fn (Throwable $throwable) => (new LaravelDocumentationLinkFinder())->findLinkForThrowable($throwable)
            )
            ->setFlare(app(Flare::class))
            ->setConfig(app(IgnitionConfig::class))
            ->setSolutionProviderRepository(app(SolutionProviderRepository::class))
            ->setContextProviderDetector(new LaravelContextProviderDetector())
            ->setSolutionTransformerClass(LaravelSolutionTransformer::class)
            ->applicationPath(base_path())
            ->addCustomHtmlToHead($viteJsAutoRefresh)
            ->renderException($throwable);
    }
}

<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Illuminate\Routing\Router;
use Throwable;
use Illuminate\Support\Str;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class ControllerClassifier implements Classifier
{
    public function name(): string
    {
        return 'Controllers';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return collect(app(Router::class)->getRoutes())
            ->reject(function ($route) {
                if (method_exists($route, 'getActionName')) {
                    // Laravel
                    return $route->getActionName() === 'Closure';
                }

                // Lumen
                return data_get($route, 'action.uses') === null;
            })
            ->map(function ($route) {
                if (method_exists($route, 'getController')) {
                    // Laravel
                    try {
                        return get_class($route->getController());
                    } catch (Throwable $e) {
                        return;
                    }
                }

                // Lumen
                return Str::before(data_get($route, 'action.uses'), '@');
            })
            ->unique()
            ->filter()
            ->contains($class->getName());
    }

    public function countsTowardsApplicationCode(): bool
    {
        return true;
    }

    public function countsTowardsTests(): bool
    {
        return false;
    }
}

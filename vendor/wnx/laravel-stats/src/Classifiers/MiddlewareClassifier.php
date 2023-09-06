<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use ReflectionProperty;
use Illuminate\Contracts\Http\Kernel;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;
use Illuminate\Contracts\Container\BindingResolutionException;

class MiddlewareClassifier implements Classifier
{
    protected $httpKernel;

    public function name(): string
    {
        return 'Middleware';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        $this->httpKernel = $this->getHttpKernelInstance();

        $middleware = $this->getMiddleware();

        if (in_array($class->getName(), $middleware)) {
            return true;
        }

        return collect($middleware)
            ->merge($this->getMiddlewareGroupsFromKernel())
            ->flatten()
            ->contains($class->getName());
    }

    protected function getMiddleware(): array
    {
        $reflection = new ReflectionProperty($this->httpKernel, 'middleware');
        $reflection->setAccessible(true);
        $middleware = $reflection->getValue($this->httpKernel);

        $reflection = new ReflectionProperty($this->httpKernel, 'routeMiddleware');
        $reflection->setAccessible(true);
        $routeMiddlwares = $reflection->getValue($this->httpKernel);

        return array_values(array_unique(array_merge($middleware, $routeMiddlwares)));
    }

    protected function getMiddlewareGroupsFromKernel(): array
    {
        $property = property_exists($this->httpKernel, 'middlewareGroups')
            ? 'middlewareGroups'
            : 'routeMiddleware';

        $reflection = new ReflectionProperty($this->httpKernel, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($this->httpKernel);
    }

    protected function getHttpKernelInstance()
    {
        try {
            return app(Kernel::class);
        } catch (BindingResolutionException $e) {
            // Lumen
            return app();
        }
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

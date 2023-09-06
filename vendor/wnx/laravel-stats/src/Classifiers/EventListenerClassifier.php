<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Closure;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use ReflectionFunction;
use ReflectionProperty;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Contracts\Classifier;

class EventListenerClassifier implements Classifier
{
    public function name(): string
    {
        return 'Event Listeners';
    }

    /**
     * @throws \ReflectionException
     */
    public function satisfies(ReflectionClass $class): bool
    {
        return collect($this->getEvents())
            ->map(function (array $listeners) {
                return collect($listeners)->map(function ($closure) {
                    return $this->getEventListener($closure);
                })->toArray();
            })
            ->collapse()
            ->flatten()
            ->unique()
            ->contains($class->getName());
    }

    /**
     * @throws \ReflectionException
     */
    protected function getEvents(): array
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = app('events');

        if (method_exists($dispatcher, 'getRawListeners')) {
            return $dispatcher->getRawListeners();
        }

        $property = new ReflectionProperty($dispatcher, 'listeners');
        $property->setAccessible(true);

        return $property->getValue($dispatcher);
    }

    /**
     * @param Closure|string $closure
     * @retrun null|string|object
     * @throws \ReflectionException
     */
    protected function getEventListener($closure)
    {
        if (is_string($closure)) {
            return $closure;
        }

        if (is_array($closure)) {
            return head($closure);
        }

        $reflection = new ReflectionFunction($closure);

        return Arr::get($reflection->getStaticVariables(), 'listener');
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

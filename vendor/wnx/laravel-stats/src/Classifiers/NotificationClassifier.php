<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Illuminate\Notifications\Notification;
use Wnx\LaravelStats\Contracts\Classifier;

class NotificationClassifier implements Classifier
{
    public function name(): string
    {
        return 'Notifications';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        if (! class_exists(Notification::class)) {
            return false;
        }

        return $class->isSubclassOf(Notification::class);
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

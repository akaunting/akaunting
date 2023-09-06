<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Classifiers;

use Wnx\LaravelStats\ReflectionClass;
use Illuminate\Contracts\Mail\Mailable;
use Wnx\LaravelStats\Contracts\Classifier;

class MailClassifier implements Classifier
{
    public function name(): string
    {
        return 'Mails';
    }

    public function satisfies(ReflectionClass $class): bool
    {
        return $class->isSubclassOf(Mailable::class);
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

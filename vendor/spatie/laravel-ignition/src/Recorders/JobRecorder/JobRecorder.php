<?php

namespace Spatie\LaravelIgnition\Recorders\JobRecorder;

use DateTime;
use Error;
use Exception;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Jobs\RedisJob;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;

class JobRecorder
{
    protected ?Job $job = null;

    public function __construct(
        protected Application $app,
        protected int $maxChainedJobReportingDepth = 5,
    ) {
    }

    public function start(): self
    {
        /** @phpstan-ignore-next-line */
        $this->app['events']->listen(JobExceptionOccurred::class, [$this, 'record']);

        return $this;
    }

    public function record(JobExceptionOccurred $event): void
    {
        $this->job = $event->job;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getJob(): ?array
    {
        if ($this->job === null) {
            return null;
        }

        return array_merge(
            $this->getJobProperties(),
            [
                'name' => $this->job->resolveName(),
                'connection' => $this->job->getConnectionName(),
                'queue' => $this->job->getQueue(),
            ]
        );
    }

    public function reset(): void
    {
        $this->job = null;
    }

    protected function getJobProperties(): array
    {
        $payload = collect($this->resolveJobPayload());

        $properties = [];

        foreach ($payload as $key => $value) {
            if (! in_array($key, ['job', 'data', 'displayName'])) {
                $properties[$key] = $value;
            }
        }

        if ($pushedAt = DateTime::createFromFormat('U.u', $payload->get('pushedAt', ''))) {
            $properties['pushedAt'] = $pushedAt->format(DATE_ATOM);
        }

        try {
            $properties['data'] = $this->resolveCommandProperties(
                $this->resolveObjectFromCommand($payload['data']['command']),
                $this->maxChainedJobReportingDepth
            );
        } catch (Exception $exception) {
        }

        return $properties;
    }

    protected function resolveJobPayload(): array
    {
        if (! $this->job instanceof RedisJob) {
            return $this->job->payload();
        }

        try {
            return json_decode($this->job->getReservedJob(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            return $this->job->payload();
        }
    }

    protected function resolveCommandProperties(object $command, int $maxChainDepth): array
    {
        $propertiesToIgnore = ['job', 'closure'];

        $properties = collect((new ReflectionClass($command))->getProperties())
            ->reject(function (ReflectionProperty $property) use ($propertiesToIgnore) {
                return in_array($property->name, $propertiesToIgnore);
            })
            ->mapWithKeys(function (ReflectionProperty $property) use ($command) {
                try {
                    $property->setAccessible(true);

                    return [$property->name => $property->getValue($command)];
                } catch (Error $error) {
                    return [$property->name => 'uninitialized'];
                }
            });

        if ($properties->has('chained')) {
            $properties['chained'] = $this->resolveJobChain($properties->get('chained'), $maxChainDepth);
        }

        return $properties->all();
    }

    /**
     * @param array<string, mixed> $chainedCommands
     * @param int $maxDepth
     *
     * @return array
     */
    protected function resolveJobChain(array $chainedCommands, int $maxDepth): array
    {
        if ($maxDepth === 0) {
            return ['Ignition stopped recording jobs after this point since the max chain depth was reached'];
        }

        return array_map(
            function (string $command) use ($maxDepth) {
                $commandObject = $this->resolveObjectFromCommand($command);

                return [
                    'name' => $commandObject instanceof CallQueuedClosure ? $commandObject->displayName() : get_class($commandObject),
                    'data' => $this->resolveCommandProperties($commandObject, $maxDepth - 1),
                ];
            },
            $chainedCommands
        );
    }

    // Taken from Illuminate\Queue\CallQueuedHandler
    protected function resolveObjectFromCommand(string $command): object
    {
        if (Str::startsWith($command, 'O:')) {
            return unserialize($command);
        }

        if ($this->app->bound(Encrypter::class)) {
            /** @phpstan-ignore-next-line */
            return unserialize($this->app[Encrypter::class]->decrypt($command));
        }

        throw new RuntimeException('Unable to extract job payload.');
    }
}

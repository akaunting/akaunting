<?php

namespace Spatie\LaravelIgnition\Recorders\QueryRecorder;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Events\QueryExecuted;

class QueryRecorder
{
    /** @var \Spatie\LaravelIgnition\Recorders\QueryRecorder\Query[] */
    protected array $queries = [];

    protected Application $app;

    protected bool $reportBindings = true;

    protected ?int $maxQueries;

    public function __construct(
        Application $app,
        bool $reportBindings = true,
        ?int $maxQueries = 200
    ) {
        $this->app = $app;
        $this->reportBindings = $reportBindings;
        $this->maxQueries = $maxQueries;
    }

    public function start(): self
    {
        /** @phpstan-ignore-next-line  */
        $this->app['events']->listen(QueryExecuted::class, [$this, 'record']);

        return $this;
    }

    public function record(QueryExecuted $queryExecuted): void
    {
        $this->queries[] = Query::fromQueryExecutedEvent($queryExecuted, $this->reportBindings);

        if (is_int($this->maxQueries)) {
            $this->queries = array_slice($this->queries, -$this->maxQueries);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getQueries(): array
    {
        $queries = [];

        foreach ($this->queries as $query) {
            $queries[] = $query->toArray();
        }

        return $queries;
    }

    public function reset(): void
    {
        $this->queries = [];
    }

    public function getReportBindings(): bool
    {
        return $this->reportBindings;
    }

    public function setReportBindings(bool $reportBindings): self
    {
        $this->reportBindings = $reportBindings;

        return $this;
    }

    public function getMaxQueries(): ?int
    {
        return $this->maxQueries;
    }

    public function setMaxQueries(?int $maxQueries): self
    {
        $this->maxQueries = $maxQueries;

        return $this;
    }
}

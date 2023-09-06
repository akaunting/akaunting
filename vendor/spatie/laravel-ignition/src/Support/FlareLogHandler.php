<?php

namespace Spatie\LaravelIgnition\Support;

use InvalidArgumentException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Spatie\FlareClient\Flare;
use Spatie\FlareClient\Report;
use Throwable;

class FlareLogHandler extends AbstractProcessingHandler
{
    protected Flare $flare;

    protected SentReports $sentReports;

    protected int $minimumReportLogLevel;

    public function __construct(Flare $flare, SentReports $sentReports, $level = Level::Debug, $bubble = true)
    {
        $this->flare = $flare;

        $this->minimumReportLogLevel = Level::Error->value;

        $this->sentReports = $sentReports;

        parent::__construct($level, $bubble);
    }

    public function setMinimumReportLogLevel(int $level): void
    {
        if (! in_array($level, Level::VALUES)) {
            throw new InvalidArgumentException('The given minimum log level is not supported.');
        }

        $this->minimumReportLogLevel = $level;
    }

    protected function write(LogRecord $record): void
    {
        if (! $this->shouldReport($record->toArray())) {
            return;
        }
        if ($this->hasException($record->toArray())) {
            $report = $this->flare->report($record['context']['exception']);

            if ($report) {
                $this->sentReports->add($report);
            }

            return;
        }

        if (config('flare.send_logs_as_events')) {
            if ($this->hasValidLogLevel($record->toArray())) {
                $this->flare->reportMessage(
                    $record['message'],
                    'Log ' . Logger::toMonologLevel($record['level'])->getName(),
                    function (Report $flareReport) use ($record) {
                        foreach ($record['context'] as $key => $value) {
                            $flareReport->context($key, $value);
                        }
                    }
                );
            }
        }
    }

    /**
     * @param array<string, mixed> $report
     *
     * @return bool
     */
    protected function shouldReport(array $report): bool
    {
        if (! config('flare.key')) {
            return false;
        }

        return $this->hasException($report) || $this->hasValidLogLevel($report);
    }

    /**
     * @param array<string, mixed> $report
     *
     * @return bool
     */
    protected function hasException(array $report): bool
    {
        $context = $report['context'];

        return isset($context['exception']) && $context['exception'] instanceof Throwable;
    }

    /**
     * @param array<string, mixed> $report
     *
     * @return bool
     */
    protected function hasValidLogLevel(array $report): bool
    {
        return $report['level'] >= $this->minimumReportLogLevel;
    }
}

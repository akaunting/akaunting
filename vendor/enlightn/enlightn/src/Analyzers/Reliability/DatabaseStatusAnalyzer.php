<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Illuminate\Support\Facades\DB;
use Throwable;

class DatabaseStatusAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "Database is accessible.";

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MAJOR;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 5;

    /**
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

    /**
     * The connections that are not accessible.
     *
     * @var string
     */
    protected $failedConnections;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application's database connection(s) is/are not accessible: ".$this->failedConnections;
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        $databaseConnectionsToCheck = config('enlightn.database_connections', [
           config('database.default'),
        ]);

        $this->failedConnections = collect($databaseConnectionsToCheck)->filter()->reject(function ($connection) {
            try {
                DB::connection($connection)->getPdo();

                return true;
            } catch (Throwable $e) {
                return false;
            }
        })->join(', ', ' and ');

        if (! empty($this->failedConnections)) {
            $this->markFailed();
        }
    }
}

<?php

namespace Enlightn\Enlightn\Reporting;

use Enlightn\Enlightn\Composer;
use Illuminate\Container\Container;
use Throwable;

class JsonReportBuilder implements ReportBuilder
{
    /**
     * @param array $analyzerResults
     * @param array $analyzerStats
     * @param array $additionalData
     * @return array
     */
    public function buildReport(array $analyzerResults, array $analyzerStats, array $additionalData = [])
    {
        return [
            'metadata' => array_merge($this->metadata(), $additionalData),
            'analyzer_results' => $analyzerResults,
            'analyzer_stats' => $analyzerStats,
        ];
    }

    /**
     * Get the project metadata for the JSON report.
     *
     * @return array
     */
    public function metadata()
    {
        return [
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_url' => config('app.url'),
            'project_name' => $this->getProjectName(),
            'github_repo' => config('enlightn.github_repo'),
        ];
    }

    /**
     * @return string|null
     */
    protected function getProjectName()
    {
        try {
            $composer = Container::getInstance()->make(Composer::class);

            $json = $composer->getJson();
        } catch (Throwable $throwable) {
            // Ignore any exceptions such as file not found.
            $json = [];
        }

        return $json['name'] ?? null;
    }
}

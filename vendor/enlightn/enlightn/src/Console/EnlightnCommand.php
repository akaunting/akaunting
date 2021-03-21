<?php

namespace Enlightn\Enlightn\Console;

use Enlightn\Enlightn\Console\Formatters\AnsiFormatter;
use Enlightn\Enlightn\Enlightn;
use Enlightn\Enlightn\Reporting\API;
use Enlightn\Enlightn\Reporting\JsonReportBuilder;
use Illuminate\Console\Command;

class EnlightnCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enlightn
                            {analyzer?* : The analyzer class that you wish to run}
                            {--details : Show details of each failed check}
                            {--ci : Run Enlightn in CI Mode}
                            {--report : Compile a report to trigger a comment by the Enlightn Github Bot}
                            {--review : Enable this for a review of the diff by the Enlightn Github Bot}
                            {--show-exceptions : Display the stack trace of exceptions if any}
                            {--issue= : The issue number of the pull request for the Enlightn Github Bot}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enlightn your application!';

    /**
     * The final result of the analysis.
     *
     * @var array
     */
    public $result = [];

    /**
     * The number of analyzers to run.
     *
     * @var int
     */
    protected $totalAnalyzers;

    /**
     * The number of analyzers that have completed their analysis.
     *
     * @var int
     */
    protected $countAnalyzers;

    /**
     * The analyzer classes to run. All classes will run if empty.
     *
     * @var array
     */
    protected $analyzerClasses;

    /**
     * @var \Enlightn\Enlightn\Console\Formatters\Formatter
     */
    protected $formatter;

    /**
     * @var array
     */
    protected $analyzerInfos = [];

    /**
     * Execute the console command.
     *
     * @param \Enlightn\Enlightn\Reporting\API $api
     * @return int
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function handle(API $api)
    {
        $this->analyzerClasses = $this->argument('analyzer');

        $this->formatter = new AnsiFormatter;

        $this->formatter->beforeAnalysis($this);

        if ($this->option('ci')) {
            Enlightn::filterAnalyzersForCI();
        }

        Enlightn::register($this->analyzerClasses);

        $this->totalAnalyzers = Enlightn::totalAnalyzers();
        $this->countAnalyzers = 1;
        $this->initializeResult();

        Enlightn::using([$this, 'printAnalyzerOutput']);
        Enlightn::run($this->laravel);

        $this->formatter->afterAnalysis($this, empty($this->analyzerClasses));

        if ($this->option('report')) {
            $reportBuilder = new JsonReportBuilder();

            $metadata = [];

            if ($github_issue = $this->option('issue')) {
                $metadata = compact('github_issue');
            }

            if ($this->option('review')) {
                $metadata['needs_review'] = true;
            }

            $api->sendReport($reportBuilder->buildReport($this->analyzerInfos, $this->result, $metadata));
        }

        // Exit with a non-zero exit code if there were failed checks to throw an error on CI environments
        return collect($this->result)->sum(function ($category) {
            return $category['reported'];
        }) == 0 ? 0 : 1;
    }

    /**
     * @param array $info
     *
     * @return void
     */
    public function printAnalyzerOutput(array $info)
    {
        $this->analyzerInfos[] = $info;

        $this->formatter->parseAnalyzerResult(
            $this,
            $info,
            $this->countAnalyzers,
            $this->totalAnalyzers,
            empty($this->analyzerClasses)
        );

        $this->updateResult($info);

        $this->countAnalyzers++;
    }

    /**
     * Initialize the result.
     *
     * @return $this
     */
    protected function initializeResult()
    {
        $this->result = [];

        foreach (array_merge(Enlightn::$categories, ['Total']) as $category) {
            $this->result[$category] = [
                'passed' => 0,
                'failed' => 0,
                'skipped' => 0,
                'error' => 0,
                'reported' => 0,
            ];
        }

        return $this;
    }

    /**
     * Update the result based on the analysis.
     *
     * @param array $info
     * @return string
     */
    protected function updateResult(array $info)
    {
        $this->result[$info['category']][$info['status']]++;
        $this->result['Total'][$info['status']]++;
        if ($info['status'] === 'failed' && ($info['reportable'] ?? true)) {
            $this->result[$info['category']]['reported']++;
            $this->result['Total']['reported']++;
        }
    }
}

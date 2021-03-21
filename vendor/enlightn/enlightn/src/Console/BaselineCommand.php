<?php

namespace Enlightn\Enlightn\Console;

use Enlightn\Enlightn\Analyzers\Trace;
use Enlightn\Enlightn\CodeCorrection\ConfigManipulator;
use Enlightn\Enlightn\Enlightn;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class BaselineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enlightn:baseline
                            {--ci : Run Enlightn in CI Mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Declare the currently reported errors as the baseline to avoid reporting them in subsequent runs.';

    /**
     * @var array
     */
    protected $dont_report = [];

    /**
     * @var array
     */
    protected $ignore_errors = [];

    /**
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected $progressbar;

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \ReflectionException|\Illuminate\Contracts\Container\BindingResolutionException|\Throwable
     */
    public function handle()
    {
        $this->setColors();
        $this->line(require __DIR__.DIRECTORY_SEPARATOR.'logo.php');
        $this->output->newLine();
        $this->line('Please wait while Enlightn scans your code base...');
        $this->output->newLine();

        // Reset ignored errors to establish a complete baseline.
        config()->set('enlightn.ignore_errors', []);

        if ($this->option('ci')) {
            Enlightn::filterAnalyzersForCI();
        }

        Enlightn::register();

        $this->progressbar = $this->output->createProgressBar(count(Enlightn::$analyzerClasses));

        Enlightn::using([$this, 'parseAnalyzerResult']);
        Enlightn::run($this->laravel);

        $this->progressbar->finish();
        $this->output->newLine();

        $this->updateConfig();

        return 0;
    }

    /**
     * Parse the result of each analyzer.
     *
     * @param array $info
     * @return void
     */
    public function parseAnalyzerResult(array $info)
    {
        if ($info['status'] === 'failed' && empty($info['traces'])) {
            $this->dont_report[] = $info['class'];
        }

        if (empty($info['traces'])) {
            return;
        }

        collect($info['traces'])->each(function (Trace $trace) use ($info) {
            if (is_null($trace->details)) {
                if (! in_array($info['class'], $this->dont_report)) {
                    $this->dont_report[] = $info['class'];
                }
            } else {
                if (! isset($this->ignore_errors[$info['class']])) {
                    $this->ignore_errors[$info['class']] = [];
                }

                $this->ignore_errors[$info['class']][] = [
                    'path' => Str::contains($trace->path, base_path()) ?
                        trim(Str::after($trace->path, base_path()), '/') : $trace->path,
                    'details' => $trace->details,
                ];
            }
        });

        $this->progressbar->advance();
    }

    /**
     * Update the config file for new baseline.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function updateConfig()
    {
        if (! file_exists(config_path('enlightn.php'))) {
            if (Enlightn::isPro()) {
                $this->call('vendor:publish', ['--tag' => 'enlightnpro']);
            } else {
                $this->call('vendor:publish', ['--tag' => 'enlightn']);
            }
        }

        (new ConfigManipulator)->replace(config_path('enlightn.php'), [
            'dont_report' => $this->dont_report,
            'ignore_errors' => $this->ignore_errors,
        ]);

        $this->info("Successfully updated config/enlightn.php with new baseline values.");
    }

    /**
     * Set the console colors for Enlightn's logo.
     *
     * @return void
     */
    protected function setColors()
    {
        collect([
            'e' => 'green',
            'n' => 'green',
            'l' => 'green',
            'i' => 'green',
            'g' => 'green',
            'h' => 'green',
            't' => 'green',
            'ns' => 'green',
        ])->each(function ($color, $tag) {
            $this->output->getFormatter()->setStyle($tag, new OutputFormatterStyle($color));
        });
    }
}

<?php declare(strict_types=1);

namespace Wnx\LaravelStats\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Wnx\LaravelStats\ClassesFinder;
use Wnx\LaravelStats\Outputs\AsciiTableOutput;
use Wnx\LaravelStats\Outputs\JsonOutput;
use Wnx\LaravelStats\Project;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\RejectionStrategies\RejectVendorClasses;

class StatsListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats
                            {--json : Output the statistics as JSON}
                            {--c|components= : Comma separated list of components which should be displayed}
                            {--s|share : DEPRECATED Share project statistic with Laravel community <https://stats.laravelshift.com>}
                            {--name= : Name used when sharing project statistic}
                            {--dry-run : Do not make request to share statistic}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate statistics for this Laravel project';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $classes = app(ClassesFinder::class)->findAndLoadClasses();

        // Transform  Classes into ReflectionClass instances
        // Remove Classes based on the RejectionStrategy
        // Remove Classes based on the namespace
        $reflectionClasses = $classes->map(function ($class) {
            return new ReflectionClass($class);
        })->reject(function (ReflectionClass $class) {
            return app(config('stats.rejection_strategy', RejectVendorClasses::class))
                ->shouldClassBeRejected($class);
        })->unique(function (ReflectionClass  $class) {
            return $class->getFileName();
        })->reject(function (ReflectionClass $class) {
            // Never discard anonymous database migrations
            if (Str::contains($class->getName(), 'Migration@anonymous')) {
                return false;
            }

            foreach (config('stats.ignored_namespaces', []) as $namespace) {
                if (Str::startsWith($class->getNamespaceName(), $namespace)) {
                    return true;
                }
            }

            return false;
        });

        $project = new Project($reflectionClasses);

        $this->renderOutput($project);

        if ($this->option('share') === true) {
            $this->warn('The share option has been deprecated and will be removed in a future update.');
        }
    }

    private function getArrayOfComponentsToDisplay(): array
    {
        if (is_null($this->option('components'))) {
            return [];
        }

        return explode(',', $this->option('components'));
    }

    private function renderOutput(Project $project)
    {
        if ($this->option('json') === true) {
            $json = (new JsonOutput())->render(
                $project,
                $this->option('verbose'),
                $this->getArrayOfComponentsToDisplay()
            );

            $this->output->text(json_encode($json));
        } else {
            (new AsciiTableOutput($this->output))->render(
                $project,
                $this->option('verbose'),
                $this->getArrayOfComponentsToDisplay()
            );
        }
    }
}

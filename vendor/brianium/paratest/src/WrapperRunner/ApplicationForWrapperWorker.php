<?php

declare(strict_types=1);

namespace ParaTest\WrapperRunner;

use ParaTest\RunnerInterface;
use PHPUnit\Event\Facade as EventFacade;
use PHPUnit\Event\TestSuite\TestSuiteBuilder;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Logging\JUnit\JunitXmlLogger;
use PHPUnit\Logging\TeamCity\TeamCityLogger;
use PHPUnit\Logging\TestDox\TestResultCollector;
use PHPUnit\Metadata\Api\CodeCoverage as CodeCoverageMetadataApi;
use PHPUnit\Runner\CodeCoverage;
use PHPUnit\Runner\Extension\ExtensionBootstrapper;
use PHPUnit\Runner\Extension\Facade as ExtensionFacade;
use PHPUnit\Runner\Extension\PharLoader;
use PHPUnit\Runner\Filter\Factory;
use PHPUnit\Runner\TestSuiteLoader;
use PHPUnit\Runner\TestSuiteSorter;
use PHPUnit\TestRunner\TestResult\Facade as TestResultFacade;
use PHPUnit\TextUI\Configuration\Builder;
use PHPUnit\TextUI\Configuration\CodeCoverageFilterRegistry;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Configuration\PhpHandler;
use PHPUnit\TextUI\Output\Default\ProgressPrinter\ProgressPrinter;
use PHPUnit\TextUI\Output\Default\UnexpectedOutputPrinter;
use PHPUnit\TextUI\Output\DefaultPrinter;
use PHPUnit\TextUI\Output\NullPrinter;
use PHPUnit\TextUI\Output\TestDox\ResultPrinter as TestDoxResultPrinter;
use PHPUnit\TextUI\TestSuiteFilterProcessor;
use PHPUnit\Util\ExcludeList;

use function assert;
use function file_put_contents;
use function is_file;
use function mt_srand;
use function serialize;
use function str_ends_with;
use function strpos;
use function substr;

/**
 * @internal
 *
 * @codeCoverageIgnore
 */
final class ApplicationForWrapperWorker
{
    private bool $hasBeenBootstrapped = false;
    private Configuration $configuration;
    private TestResultCollector $testdoxResultCollector;

    /** @param list<string> $argv */
    public function __construct(
        private readonly array $argv,
        private readonly string $progressFile,
        private readonly string $unexpectedOutputFile,
        private readonly string $testresultFile,
        private readonly ?string $teamcityFile,
        private readonly ?string $testdoxFile,
        private readonly bool $testdoxColor,
    ) {
    }

    public function runTest(string $testPath): int
    {
        $null   = strpos($testPath, "\0");
        $filter = null;
        if ($null !== false) {
            $filter = new Factory();
            $filter->addNameFilter(substr($testPath, $null + 1));
            $testPath = substr($testPath, 0, $null);
        }

        $this->bootstrap();

        if (is_file($testPath) && str_ends_with($testPath, '.phpt')) {
            $testSuite = TestSuite::empty($testPath);
            $testSuite->addTestFile($testPath);
        } else {
            $testSuiteRefl = (new TestSuiteLoader())->load($testPath);
            $testSuite     = TestSuite::fromClassReflector($testSuiteRefl);
        }

        if (CodeCoverage::instance()->isActive()) {
            CodeCoverage::instance()->ignoreLines(
                (new CodeCoverageMetadataApi())->linesToBeIgnored($testSuite),
            );
        }

        (new TestSuiteFilterProcessor())->process($this->configuration, $testSuite);

        if ($filter !== null) {
            $testSuite->injectFilter($filter);

            EventFacade::emitter()->testSuiteFiltered(
                TestSuiteBuilder::from($testSuite),
            );
        }

        EventFacade::emitter()->testRunnerExecutionStarted(
            TestSuiteBuilder::from($testSuite),
        );

        $testSuite->run();

        return TestResultFacade::result()->wasSuccessfulIgnoringPhpunitWarnings()
            ? RunnerInterface::SUCCESS_EXIT
            : RunnerInterface::FAILURE_EXIT;
    }

    private function bootstrap(): void
    {
        if ($this->hasBeenBootstrapped) {
            return;
        }

        ExcludeList::addDirectory(__DIR__);
        EventFacade::emitter()->applicationStarted();

        $this->configuration = (new Builder())->build($this->argv);

        (new PhpHandler())->handle($this->configuration->php());

        if ($this->configuration->hasBootstrap()) {
            $bootstrapFilename = $this->configuration->bootstrap();
            include_once $bootstrapFilename;
            EventFacade::emitter()->testRunnerBootstrapFinished($bootstrapFilename);
        }

        $extensionRequiresCodeCoverageCollection = false;
        if (! $this->configuration->noExtensions()) {
            if ($this->configuration->hasPharExtensionDirectory()) {
                (new PharLoader())->loadPharExtensionsInDirectory(
                    $this->configuration->pharExtensionDirectory(),
                );
            }

            $extensionFacade       = new ExtensionFacade();
            $extensionBootstrapper = new ExtensionBootstrapper(
                $this->configuration,
                $extensionFacade,
            );

            foreach ($this->configuration->extensionBootstrappers() as $bootstrapper) {
                $extensionBootstrapper->bootstrap(
                    $bootstrapper['className'],
                    $bootstrapper['parameters'],
                );
            }

            $extensionRequiresCodeCoverageCollection = $extensionFacade->requiresCodeCoverageCollection();
        }

        CodeCoverage::instance()->init(
            $this->configuration,
            CodeCoverageFilterRegistry::instance(),
            $extensionRequiresCodeCoverageCollection,
        );

        if ($this->configuration->hasLogfileJunit()) {
            new JunitXmlLogger(
                DefaultPrinter::from($this->configuration->logfileJunit()),
                EventFacade::instance(),
            );
        }

        $printer = new ProgressPrinterOutput(
            DefaultPrinter::from($this->progressFile),
            DefaultPrinter::from($this->unexpectedOutputFile),
        );

        new UnexpectedOutputPrinter($printer, EventFacade::instance());
        new ProgressPrinter(
            $printer,
            EventFacade::instance(),
            false,
            120,
            $this->configuration->source(),
        );

        if (isset($this->teamcityFile)) {
            new TeamCityLogger(
                DefaultPrinter::from($this->teamcityFile),
                EventFacade::instance(),
            );
        }

        if (isset($this->testdoxFile)) {
            $this->testdoxResultCollector = new TestResultCollector(EventFacade::instance());
        }

        TestResultFacade::init();
        EventFacade::instance()->seal();
        EventFacade::emitter()->testRunnerStarted();

        if ($this->configuration->executionOrder() === TestSuiteSorter::ORDER_RANDOMIZED) {
            mt_srand($this->configuration->randomOrderSeed());
        }

        $this->hasBeenBootstrapped = true;
    }

    public function end(): void
    {
        EventFacade::emitter()->testRunnerExecutionFinished();
        EventFacade::emitter()->testRunnerFinished();

        CodeCoverage::instance()->generateReports(new NullPrinter(), $this->configuration);

        $result = TestResultFacade::result();
        if (isset($this->testdoxResultCollector)) {
            assert(isset($this->testdoxFile));

            (new TestDoxResultPrinter(DefaultPrinter::from($this->testdoxFile), $this->testdoxColor))->print(
                $this->testdoxResultCollector->testMethodsGroupedByClass(),
            );
        }

        file_put_contents($this->testresultFile, serialize($result));

        EventFacade::emitter()->applicationFinished(0);
    }
}

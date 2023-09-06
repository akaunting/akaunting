<?php

declare(strict_types=1);

namespace ParaTest;

use Fidry\CpuCoreCounter\CpuCoreCounter;
use Fidry\CpuCoreCounter\NumberOfCpuCoreNotFound;
use PHPUnit\TextUI\Configuration\Builder;
use PHPUnit\TextUI\Configuration\Configuration;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use function array_filter;
use function array_intersect_key;
use function array_key_exists;
use function array_shift;
use function assert;
use function count;
use function dirname;
use function escapeshellarg;
use function file_exists;
use function is_array;
use function is_bool;
use function is_numeric;
use function is_string;
use function realpath;
use function sprintf;
use function str_starts_with;
use function strlen;
use function substr;
use function sys_get_temp_dir;
use function uniqid;
use function unserialize;

use const PHP_BINARY;

/**
 * @internal
 *
 * @immutable
 */
final class Options
{
    public const ENV_KEY_TOKEN        = 'TEST_TOKEN';
    public const ENV_KEY_UNIQUE_TOKEN = 'UNIQUE_TEST_TOKEN';

    private const OPTIONS_TO_KEEP_FOR_PHPUNIT_IN_WORKER = [
        'bootstrap' => true,
        'cache-directory' => true,
        'configuration' => true,
        'coverage-filter' => true,
        'dont-report-useless-tests' => true,
        'exclude-group' => true,
        'fail-on-incomplete' => true,
        'fail-on-risky' => true,
        'fail-on-skipped' => true,
        'fail-on-warning' => true,
        'filter' => true,
        'group' => true,
        'no-configuration' => true,
        'order-by' => true,
        'process-isolation' => true,
        'random-order-seed' => true,
        'stop-on-defect' => true,
        'stop-on-error' => true,
        'stop-on-warning' => true,
        'stop-on-risky' => true,
        'stop-on-skipped' => true,
        'stop-on-incomplete' => true,
        'strict-coverage' => true,
        'strict-global-state' => true,
        'disallow-test-output' => true,
    ];

    public readonly bool $needsTeamcity;

    /**
     * @param non-empty-string                               $phpunit
     * @param non-empty-string                               $cwd
     * @param list<non-empty-string>|null                    $passthruPhp
     * @param array<non-empty-string, non-empty-string|true> $phpunitOptions
     * @param non-empty-string                               $runner
     * @param non-empty-string                               $tmpDir
     */
    private function __construct(
        public readonly Configuration $configuration,
        public readonly string $phpunit,
        public readonly string $cwd,
        public readonly int $maxBatchSize,
        public readonly bool $noTestTokens,
        public readonly ?array $passthruPhp,
        public readonly array $phpunitOptions,
        public readonly int $processes,
        public readonly string $runner,
        public readonly string $tmpDir,
        public readonly bool $verbose,
        public readonly bool $functional,
    ) {
        $this->needsTeamcity = $configuration->outputIsTeamCity() || $configuration->hasLogfileTeamcity();
    }

    /** @param non-empty-string $cwd */
    public static function fromConsoleInput(InputInterface $input, string $cwd): self
    {
        $options = $input->getOptions();

        $maxBatchSize = (int) $options['max-batch-size'];
        unset($options['max-batch-size']);

        assert(is_bool($options['no-test-tokens']));
        $noTestTokens = $options['no-test-tokens'];
        unset($options['no-test-tokens']);

        assert($options['passthru-php'] === null || is_string($options['passthru-php']));
        $passthruPhp = self::parsePassthru($options['passthru-php']);
        unset($options['passthru-php']);

        assert(is_string($options['processes']));
        $processes = is_numeric($options['processes'])
            ? (int) $options['processes']
            : self::getNumberOfCPUCores();
        unset($options['processes']);

        assert(is_string($options['runner']) && $options['runner'] !== '');
        $runner = $options['runner'];
        unset($options['runner']);

        assert(is_string($options['tmp-dir']) && $options['tmp-dir'] !== '');
        $tmpDir = $options['tmp-dir'];
        unset($options['tmp-dir']);

        assert(is_bool($options['verbose']));
        $verbose = $options['verbose'];
        unset($options['verbose']);

        assert(is_bool($options['functional']));
        $functional = $options['functional'];
        unset($options['functional']);

        assert(array_key_exists('colors', $options));
        if ($options['colors'] === Configuration::COLOR_DEFAULT) {
            unset($options['colors']);
        } elseif ($options['colors'] === null) {
            $options['colors'] = Configuration::COLOR_AUTO;
        }

        assert(array_key_exists('coverage-text', $options));
        if ($options['coverage-text'] === null) {
            $options['coverage-text'] = 'php://stdout';
        }

        // Must be a static non-customizable reference because ParaTest code
        // is strictly coupled with PHPUnit pinned version
        $phpunit = self::getPhpunitBinary();
        if (str_starts_with($phpunit, $cwd)) {
            $phpunit = substr($phpunit, 1 + strlen($cwd));
        }

        $phpunitArgv = [$phpunit];
        foreach ($options as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }

            if ($value === true) {
                $phpunitArgv[] = "--{$key}";
                continue;
            }

            $phpunitArgv[] = "--{$key}={$value}";
        }

        if (($path = $input->getArgument('path')) !== null) {
            $phpunitArgv[] = '--';
            $phpunitArgv[] = $path;
        }

        $phpunitOptions = array_intersect_key($options, self::OPTIONS_TO_KEEP_FOR_PHPUNIT_IN_WORKER);
        $phpunitOptions = array_filter($phpunitOptions);

        $configuration = (new Builder())->build($phpunitArgv);

        return new self(
            $configuration,
            $phpunit,
            $cwd,
            $maxBatchSize,
            $noTestTokens,
            $passthruPhp,
            $phpunitOptions,
            $processes,
            $runner,
            $tmpDir,
            $verbose,
            $functional,
        );
    }

    public static function setInputDefinition(InputDefinition $inputDefinition): void
    {
        $inputDefinition->setDefinition([
            // Arguments
            new InputArgument(
                'path',
                InputArgument::OPTIONAL,
                'The path to a directory or file containing tests.',
            ),

            // ParaTest options
            new InputOption(
                'functional',
                null,
                InputOption::VALUE_NONE,
                'Whether to enable functional testing, for unit and dataset parallelization',
            ),
            new InputOption(
                'max-batch-size',
                'm',
                InputOption::VALUE_REQUIRED,
                'Max batch size.',
                '0',
            ),
            new InputOption(
                'no-test-tokens',
                null,
                InputOption::VALUE_NONE,
                'Disable TEST_TOKEN environment variables.',
            ),
            new InputOption(
                'passthru-php',
                null,
                InputOption::VALUE_REQUIRED,
                'Pass the given arguments verbatim to the underlying php process. Example: --passthru-php="\'-d\' ' .
                '\'pcov.enabled=1\'"',
            ),
            new InputOption(
                'processes',
                'p',
                InputOption::VALUE_REQUIRED,
                'The number of test processes to run.',
                'auto',
            ),
            new InputOption(
                'runner',
                null,
                InputOption::VALUE_REQUIRED,
                sprintf('A %s.', RunnerInterface::class),
                'WrapperRunner',
            ),
            new InputOption(
                'tmp-dir',
                null,
                InputOption::VALUE_REQUIRED,
                'Temporary directory for internal ParaTest files',
                sys_get_temp_dir(),
            ),
            new InputOption(
                'verbose',
                'v',
                InputOption::VALUE_NONE,
                'Output more verbose information',
            ),

            // PHPUnit options
            new InputOption(
                'bootstrap',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter = 'Configuration',
            ),
            new InputOption(
                'configuration',
                'c',
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'no-configuration',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'cache-directory',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'testsuite',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter = 'Selection',
            ),
            new InputOption(
                'exclude-testsuite',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'group',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'exclude-group',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'filter',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'process-isolation',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter = 'Execution',
            ),
            new InputOption(
                'strict-coverage',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'strict-global-state',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'disallow-test-output',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'dont-report-useless-tests',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'stop-on-defect',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'stop-on-error',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'stop-on-failure',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'stop-on-warning',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'stop-on-risky',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'stop-on-skipped',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'stop-on-incomplete',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'fail-on-incomplete',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'fail-on-risky',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'fail-on-skipped',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'fail-on-warning',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'order-by',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'random-order-seed',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'colors',
                null,
                InputOption::VALUE_OPTIONAL,
                '@see PHPUnit guide, chapter: ' . $chapter = 'Reporting',
                Configuration::COLOR_DEFAULT,
            ),
            new InputOption(
                'no-progress',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'display-incomplete',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'display-skipped',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'display-deprecations',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'display-errors',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'display-notices',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'display-warnings',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'teamcity',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'testdox',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'log-junit',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter = 'Logging',
            ),
            new InputOption(
                'log-teamcity',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'coverage-clover',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter = 'Code Coverage',
            ),
            new InputOption(
                'coverage-cobertura',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'coverage-crap4j',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'coverage-html',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'coverage-php',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'coverage-text',
                null,
                InputOption::VALUE_OPTIONAL,
                '@see PHPUnit guide, chapter: ' . $chapter,
                false,
            ),
            new InputOption(
                'coverage-xml',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'coverage-filter',
                null,
                InputOption::VALUE_REQUIRED,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
            new InputOption(
                'no-coverage',
                null,
                InputOption::VALUE_NONE,
                '@see PHPUnit guide, chapter: ' . $chapter,
            ),
        ]);
    }

    /** @return non-empty-string $phpunit the path to phpunit */
    private static function getPhpunitBinary(): string
    {
        $tryPaths = [
            dirname(__DIR__, 3) . '/bin/phpunit',
            dirname(__DIR__, 3) . '/phpunit/phpunit/phpunit',
            dirname(__DIR__) . '/vendor/phpunit/phpunit/phpunit',
        ];

        foreach ($tryPaths as $path) {
            if (($realPath = realpath($path)) !== false && file_exists($realPath)) {
                return $realPath;
            }
        }

        throw new RuntimeException('PHPUnit not found'); // @codeCoverageIgnore
    }

    public static function getNumberOfCPUCores(): int
    {
        try {
            return (new CpuCoreCounter())->getCount();
        } catch (NumberOfCpuCoreNotFound) {
            return 2;
        }
    }

    /** @return list<non-empty-string>|null */
    private static function parsePassthru(?string $param): ?array
    {
        if ($param === null) {
            return null;
        }

        $stringToArgumentProcess = Process::fromShellCommandline(
            sprintf(
                '%s -r %s -- %s',
                escapeshellarg(PHP_BINARY),
                escapeshellarg('echo serialize($argv);'),
                $param,
            ),
        );
        $stringToArgumentProcess->mustRun();

        $passthruAsArguments = unserialize($stringToArgumentProcess->getOutput());
        assert(is_array($passthruAsArguments));
        array_shift($passthruAsArguments);

        if (count($passthruAsArguments) === 0) {
            return null;
        }

        return $passthruAsArguments;
    }

    /** @return array{PARATEST: int, TEST_TOKEN?: int, UNIQUE_TEST_TOKEN?: non-empty-string} */
    public function fillEnvWithTokens(int $inc): array
    {
        $env = ['PARATEST' => 1];
        if (! $this->noTestTokens) {
            $env[self::ENV_KEY_TOKEN]        = $inc;
            $env[self::ENV_KEY_UNIQUE_TOKEN] = uniqid($inc . '_');
        }

        return $env;
    }
}

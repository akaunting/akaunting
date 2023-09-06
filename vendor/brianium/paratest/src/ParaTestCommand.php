<?php

declare(strict_types=1);

namespace ParaTest;

use InvalidArgumentException;
use Jean85\PrettyVersions;
use ParaTest\WrapperRunner\WrapperRunner;
use PHPUnit\Runner\Version;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function assert;
use function class_exists;
use function is_string;
use function is_subclass_of;
use function sprintf;

/** @internal */
final class ParaTestCommand extends Command
{
    public const COMMAND_NAME = 'paratest';

    private const KNOWN_RUNNERS = [
        'WrapperRunner' => WrapperRunner::class,
    ];

    /** @param non-empty-string $cwd */
    public function __construct(
        private readonly string $cwd,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    /** @param non-empty-string $cwd */
    public static function applicationFactory(string $cwd): Application
    {
        $application = new Application();
        $command     = new self($cwd, self::COMMAND_NAME);

        $application->setName('ParaTest');
        $application->setVersion(PrettyVersions::getVersion('brianium/paratest')->getPrettyVersion());
        $application->add($command);
        $commandName = $command->getName();
        assert($commandName !== null);
        $application->setDefaultCommand($commandName, true);

        return $application;
    }

    protected function configure(): void
    {
        Options::setInputDefinition($this->getDefinition());
    }

    /**
     * {@inheritDoc}
     */
    public function mergeApplicationDefinition($mergeArgs = true): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();
        assert($application !== null);

        $output->write(sprintf(
            "%s upon %s\n\n",
            $application->getLongVersion(),
            Version::getVersionString(),
        ));

        $options = Options::fromConsoleInput(
            $input,
            $this->cwd,
        );
        if (! $options->configuration->hasConfigurationFile() && ! $options->configuration->hasCliArgument()) {
            return $this->displayHelp($output);
        }

        $runnerClass = $this->getRunnerClass($input);

        return (new $runnerClass($options, $output))->run();
    }

    private function displayHelp(OutputInterface $output): int
    {
        $app = $this->getApplication();
        assert($app !== null);
        $help  = $app->find('help');
        $input = new ArrayInput(['command_name' => $this->getName()]);

        return $help->run($input, $output);
    }

    /** @return class-string<RunnerInterface> */
    private function getRunnerClass(InputInterface $input): string
    {
        $runnerClass = $input->getOption('runner');
        assert(is_string($runnerClass));
        $runnerClass = self::KNOWN_RUNNERS[$runnerClass] ?? $runnerClass;

        if (! class_exists($runnerClass) || ! is_subclass_of($runnerClass, RunnerInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Selected runner class "%s" does not exist or does not implement %s',
                $runnerClass,
                RunnerInterface::class,
            ));
        }

        return $runnerClass;
    }
}

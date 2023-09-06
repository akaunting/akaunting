<?php

namespace Sentry\Laravel\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use RuntimeException;
use Sentry\Dsn;
use Sentry\Laravel\ServiceProvider;
use Symfony\Component\Process\Process;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sentry:publish {--dsn=}
                                           {--without-performance-monitoring}
                                           {--without-test}
                                           {--without-javascript-sdk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes and configures the Sentry config.';

    protected const SDK_CHOICE_BROWSER = 'JavaScript (default)';
    protected const SDK_CHOICE_VUE     = 'Vue.js';
    protected const SDK_CHOICE_REACT   = 'React';
    protected const SDK_CHOICE_ANGULAR = 'Angular';
    protected const SDK_CHOICE_SVELTE  = 'Svelte';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $arg = [];
        $env = [];

        $dsn = $this->option('dsn');

        if (!empty($dsn) || !$this->isEnvKeySet('SENTRY_LARAVEL_DSN')) {
            if (empty($dsn)) {
                $dsnFromInput = $this->askForDsnInput();

                if (empty($dsnFromInput)) {
                    $this->error('Please provide a valid DSN using the `--dsn` option or setting `SENTRY_LARAVEL_DSN` in your `.env` file!');

                    return 1;
                }

                $dsn = $dsnFromInput;
            }

            $env['SENTRY_LARAVEL_DSN'] = $dsn;
            $arg['--dsn']              = $dsn;
        }

        $testCommandPrompt = 'Do you want to send a test event to Sentry?';

        if ($this->confirm('Enable Performance Monitoring?', !$this->option('without-performance-monitoring'))) {
            $testCommandPrompt = 'Do you want to send a test event & transaction to Sentry?';

            $env['SENTRY_TRACES_SAMPLE_RATE'] = '1.0';

            $arg['--transaction'] = true;
        } elseif ($this->isEnvKeySet('SENTRY_TRACES_SAMPLE_RATE')) {
            $env['SENTRY_TRACES_SAMPLE_RATE'] = '0';
        }

        if ($this->confirm($testCommandPrompt, !$this->option('without-test'))) {
            $testResult = $this->call('sentry:test', $arg);

            if ($testResult === 1) {
                return 1;
            }
        }

        $this->info('Publishing Sentry config...');
        $this->call('vendor:publish', ['--provider' => ServiceProvider::class]);

        if (!$this->setEnvValues($env)) {
            return 1;
        }
        if ($this->confirm('Do you want to install one of our JavaScript SDKs?', !$this->option('without-javascript-sdk'))) {
            $this->installJavaScriptSdk();
        }

        return 0;
    }

    private function setEnvValues(array $values): bool
    {
        $envFilePath = app()->environmentFilePath();

        $envFileContents = file_get_contents($envFilePath);

        if (!$envFileContents) {
            $this->error('Could not read `.env` file!');

            return false;
        }

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                if ($this->isEnvKeySet($envKey, $envFileContents)) {
                    $envFileContents = preg_replace("/^{$envKey}=.*?[\s$]/m", "{$envKey}={$envValue}\n", $envFileContents);

                    $this->info("Updated {$envKey} with new value in your `.env` file.");
                } else {
                    $envFileContents .= "{$envKey}={$envValue}\n";

                    $this->info("Added {$envKey} to your `.env` file.");
                }
            }
        }

        if (!file_put_contents($envFilePath, $envFileContents)) {
            $this->error('Updating the `.env` file failed!');

            return false;
        }

        return true;
    }

    private function isEnvKeySet(string $envKey, ?string $envFileContents = null): bool
    {
        $envFileContents = $envFileContents ?? file_get_contents(app()->environmentFilePath());

        return (bool)preg_match("/^{$envKey}=.*?[\s$]/m", $envFileContents);
    }

    private function askForDsnInput(): string
    {
        if ($this->option('no-interaction')) {
            return '';
        }

        while (true) {
            $this->info('');

            $this->question('Please paste the DSN here');

            $dsn = $this->ask('DSN');

            // In case someone copies it with SENTRY_LARAVEL_DSN= or SENTRY_DSN=
            $dsn = Str::after($dsn, '=');

            try {
                Dsn::createFromString($dsn);

                return $dsn;
            } catch (Exception $e) {
                // Not a valid DSN do it again
                $this->error('The DSN is not valid, please make sure to paste a valid DSN!');
            }
        }
    }

    private function installJavaScriptSdk(): void
    {
        $framework = $this->choice(
            'Which frontend framework are you using?',
            [
                self::SDK_CHOICE_BROWSER,
                self::SDK_CHOICE_VUE,
                self::SDK_CHOICE_REACT,
                self::SDK_CHOICE_ANGULAR,
                self::SDK_CHOICE_SVELTE,
            ],
            self::SDK_CHOICE_BROWSER
        );

        $snippet = '';

        switch ($framework) {
            case self::SDK_CHOICE_BROWSER:
                $this->updateNodePackages(function ($packages) {
                    return [
                        '@sentry/browser' => '^7.40.0',
                    ] + $packages;
                });
                $snippet = file_get_contents(__DIR__ . '/../../../../stubs/sentry-javascript/browser.js');
                break;
            case self::SDK_CHOICE_VUE:
                $this->updateNodePackages(function ($packages) {
                    return [
                        '@sentry/vue' => '^7.40.0',
                    ] + $packages;
                });
                $snippet = file_get_contents(__DIR__ . '/../../../../stubs/sentry-javascript/vue.js');
                break;
            case self::SDK_CHOICE_REACT:
                $this->updateNodePackages(function ($packages) {
                    return [
                        '@sentry/react' => '^7.40.0',
                    ] + $packages;
                });
                $snippet = file_get_contents(__DIR__ . '/../../../../stubs/sentry-javascript/react.js');
                break;
            case self::SDK_CHOICE_ANGULAR:
                $this->updateNodePackages(function ($packages) {
                    return [
                        '@sentry/angular' => '^7.40.0',
                    ] + $packages;
                });
                $snippet = file_get_contents(__DIR__ . '/../../../../stubs/sentry-javascript/angular.js');
                break;
            case self::SDK_CHOICE_SVELTE:
                $this->updateNodePackages(function ($packages) {
                    return [
                        '@sentry/svelte' => '^7.40.0',
                    ] + $packages;
                });
                $snippet = file_get_contents(__DIR__ . '/../../../../stubs/sentry-javascript/svelte.js');
                break;
        }

        $env['VITE_SENTRY_DSN_PUBLIC'] ='"${SENTRY_LARAVEL_DSN}"';
        $this->setEnvValues($env);

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install']);
        } else {
            $this->runCommands(['npm install']);
        }

        $this->newLine();
        $this->components->info('Sentry JavaScript SDK installed successfully.');
        $this->line('Put the following snippet into your JavaScript entry file:');
        $this->newLine();
        $this->line('<bg=blue>' . $snippet . '</>');
        $this->newLine();
        $this->line('For the best Sentry experience, we recommend you to set up dedicated projects for your Laravel and JavaScript applications.');
    }

    private function updateNodePackages(callable $callback)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages['dependencies'] = $callback(
            array_key_exists('dependencies', $packages) ? $packages['dependencies'] : [],
            'dependencies'
        );

        ksort($packages['dependencies']);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    private function runCommands($commands)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }
}

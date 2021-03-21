<?php

/**
 * This file is part of the Dealerdirect PHP_CodeSniffer Standards
 * Composer Installer Plugin package.
 *
 * @copyright 2016-2020 Dealerdirect B.V.
 * @license MIT
 */

namespace Dealerdirect\Composer\Plugin\Installers\PHPCodeSniffer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\AliasPackage;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\Filesystem;
use Composer\Util\ProcessExecutor;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * PHP_CodeSniffer standard installation manager.
 *
 * @author Franck Nijhof <franck.nijhof@dealerdirect.com>
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{

    const KEY_MAX_DEPTH = 'phpcodesniffer-search-depth';

    const MESSAGE_ERROR_WRONG_MAX_DEPTH =
        'The value of "%s" (in the composer.json "extra".section) must be an integer larger then %d, %s given.';
    const MESSAGE_NOT_INSTALLED = 'PHPCodeSniffer is not installed';
    const MESSAGE_NOTHING_TO_INSTALL = 'Nothing to install or update';
    const MESSAGE_PLUGIN_UNINSTALLED = 'PHPCodeSniffer Composer Installer is uninstalled';
    const MESSAGE_RUNNING_INSTALLER = 'Running PHPCodeSniffer Composer Installer';

    const PACKAGE_NAME = 'squizlabs/php_codesniffer';
    const PACKAGE_TYPE = 'phpcodesniffer-standard';

    const PHPCS_CONFIG_REGEX = '`%s:[^\r\n]+`';
    const PHPCS_CONFIG_KEY = 'installed_paths';

    const PLUGIN_NAME = 'dealerdirect/phpcodesniffer-composer-installer';

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var string
     */
    private $cwd;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $installedPaths;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var ProcessExecutor
     */
    private $processExecutor;

    /**
     * Triggers the plugin's main functionality.
     *
     * Makes it possible to run the plugin as a custom command.
     *
     * @param Event $event
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws LogicException
     * @throws ProcessFailedException
     * @throws RuntimeException
     */
    public static function run(Event $event)
    {
        $io = $event->getIO();
        $composer = $event->getComposer();

        $instance = new static();

        $instance->io = $io;
        $instance->composer = $composer;
        $instance->init();
        $instance->onDependenciesChangedEvent();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     * @throws LogicException
     * @throws ProcessFailedException
     * @throws RuntimeException
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;

        $this->init();
    }

    /**
     * {@inheritDoc}
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Prepares the plugin so it's main functionality can be run.
     *
     * @throws \RuntimeException
     * @throws LogicException
     * @throws ProcessFailedException
     * @throws RuntimeException
     */
    private function init()
    {
        $this->cwd = getcwd();
        $this->installedPaths = array();

        $this->processExecutor = new ProcessExecutor($this->io);
        $this->filesystem = new Filesystem($this->processExecutor);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            ScriptEvents::POST_INSTALL_CMD => array(
                array('onDependenciesChangedEvent', 0),
            ),
            ScriptEvents::POST_UPDATE_CMD => array(
                array('onDependenciesChangedEvent', 0),
            ),
        );
    }

    /**
     * Entry point for post install and post update events.
     *
     * @throws \InvalidArgumentException
     * @throws LogicException
     * @throws ProcessFailedException
     * @throws RuntimeException
     */
    public function onDependenciesChangedEvent()
    {
        $io = $this->io;
        $isVerbose = $io->isVerbose();
        $exitCode = 0;

        if ($isVerbose) {
            $io->write(sprintf('<info>%s</info>', self::MESSAGE_RUNNING_INSTALLER));
        }

        if ($this->isPHPCodeSnifferInstalled() === true) {
            $this->loadInstalledPaths();
            $installPathCleaned = $this->cleanInstalledPaths();
            $installPathUpdated = $this->updateInstalledPaths();

            if ($installPathCleaned === true || $installPathUpdated === true) {
                $exitCode = $this->saveInstalledPaths();
            } elseif ($isVerbose) {
                $io->write(sprintf('<info>%s</info>', self::MESSAGE_NOTHING_TO_INSTALL));
            }
        } else {
            $pluginPackage = $this
                ->composer
                ->getRepositoryManager()
                ->getLocalRepository()
                ->findPackages(self::PLUGIN_NAME)
            ;

            $isPluginUninstalled = count($pluginPackage) === 0;

            if ($isPluginUninstalled) {
                if ($isVerbose) {
                    $io->write(sprintf('<info>%s</info>', self::MESSAGE_PLUGIN_UNINSTALLED));
                }
            } else {
                $exitCode = 1;
                if ($isVerbose) {
                    $io->write(sprintf('<error>%s</error>', self::MESSAGE_NOT_INSTALLED));
                }
            }
        }

        return $exitCode;
    }

    /**
     * Load all paths from PHP_CodeSniffer into an array.
     *
     * @throws LogicException
     * @throws ProcessFailedException
     * @throws RuntimeException
     */
    private function loadInstalledPaths()
    {
        if ($this->isPHPCodeSnifferInstalled() === true) {
            $this->processExecutor->execute(
                sprintf(
                    'phpcs --config-show %s',
                    self::PHPCS_CONFIG_KEY
                ),
                $output,
                $this->composer->getConfig()->get('bin-dir')
            );

            $regex = sprintf(self::PHPCS_CONFIG_REGEX, self::PHPCS_CONFIG_KEY);
            if (preg_match($regex, $output, $match) === 1) {
                $phpcsInstalledPaths = str_replace(self::PHPCS_CONFIG_KEY . ': ', '', $match[0]);
                $phpcsInstalledPaths = trim($phpcsInstalledPaths);

                if ($phpcsInstalledPaths !== '') {
                    $this->installedPaths = explode(',', $phpcsInstalledPaths);
                }
            }
        }
    }

    /**
     * Save all coding standard paths back into PHP_CodeSniffer
     *
     * @throws LogicException
     * @throws ProcessFailedException
     * @throws RuntimeException
     *
     * @return int Exit code. 0 for success, 1 or higher for failure.
     */
    private function saveInstalledPaths()
    {
        // Check if we found installed paths to set.
        if (count($this->installedPaths) !== 0) {
            sort($this->installedPaths);
            $paths = implode(',', $this->installedPaths);
            $arguments = array('--config-set', self::PHPCS_CONFIG_KEY, $paths);
            $configMessage = sprintf(
                'PHP CodeSniffer Config <info>%s</info> <comment>set to</comment> <info>%s</info>',
                self::PHPCS_CONFIG_KEY,
                $paths
            );
        } else {
            // Delete the installed paths if none were found.
            $arguments = array('--config-delete', self::PHPCS_CONFIG_KEY);
            $configMessage = sprintf(
                'PHP CodeSniffer Config <info>%s</info> <comment>delete</comment>',
                self::PHPCS_CONFIG_KEY
            );
        }

        // Prepare message in case of failure
        $failMessage = sprintf(
            'Failed to set PHP CodeSniffer <info>%s</info> Config',
            self::PHPCS_CONFIG_KEY
        );

        // Determine the path to the main PHPCS file.
        $phpcsPath = $this->getPHPCodeSnifferInstallPath();
        if (file_exists($phpcsPath . '/bin/phpcs') === true) {
            // PHPCS 3.x.
            $phpcsExecutable = './bin/phpcs';
        } else {
            // PHPCS 2.x.
            $phpcsExecutable = './scripts/phpcs';
        }

        // Okay, lets rock!
        $command = vsprintf(
            '%s %s %s',
            array(
                'php executable'   => $this->getPhpExecCommand(),
                'phpcs executable' => $phpcsExecutable,
                'arguments'        => implode(' ', $arguments)
            )
        );

        $exitCode = $this->processExecutor->execute($command, $configResult, $phpcsPath);
        if ($exitCode === 0) {
            $exitCode = $this->verifySaveSuccess();
        }

        if ($exitCode === 0) {
            $this->io->write($configMessage);
        } else {
            $this->io->write($failMessage);
        }

        if ($this->io->isVerbose() && !empty($configResult)) {
            $this->io->write(sprintf('<info>%s</info>', $configResult));
        }

        return $exitCode;
    }

    /**
     * Verify that the paths which were expected to be saved, have been.
     *
     * @return int Exit code. 0 for success, 1 for failure.
     */
    private function verifySaveSuccess()
    {
        $exitCode      = 1;
        $expectedPaths = $this->installedPaths;

        // Request the currently set installed paths after the save.
        $this->loadInstalledPaths();

        $registeredPaths = array_intersect($this->installedPaths, $expectedPaths);
        $registeredCount = count($registeredPaths);
        $expectedCount   = count($expectedPaths);

        if ($expectedCount === $registeredCount) {
            $exitCode = 0;
        }

        if ($exitCode === 1 && $this->io->isVerbose()) {
            $verificationMessage = sprintf(
                "Paths to external standards found by the plugin: <info>%s</info>\n"
                . 'Actual paths registered with PHPCS: <info>%s</info>',
                implode(', ', $expectedPaths),
                implode(', ', $this->installedPaths)
            );
            $this->io->write($verificationMessage);
        }

        return $exitCode;
    }

    /**
     * Get the path to the current PHP version being used.
     *
     * Duplicate of the same in the EventDispatcher class in Composer itself.
     */
    protected function getPhpExecCommand()
    {
        $finder = new PhpExecutableFinder();

        $phpPath = $finder->find(false);

        if ($phpPath === false) {
            throw new \RuntimeException('Failed to locate PHP binary to execute ' . $phpPath);
        }

        $phpArgs = $finder->findArguments();
        $phpArgs = $phpArgs
            ? ' ' . implode(' ', $phpArgs)
            : ''
        ;

        $command  = ProcessExecutor::escape($phpPath) .
            $phpArgs .
            ' -d allow_url_fopen=' . ProcessExecutor::escape(ini_get('allow_url_fopen')) .
            ' -d disable_functions=' . ProcessExecutor::escape(ini_get('disable_functions')) .
            ' -d memory_limit=' . ProcessExecutor::escape(ini_get('memory_limit'))
        ;

        return $command;
    }

    /**
     * Iterate trough all known paths and check if they are still valid.
     *
     * If path does not exists, is not an directory or isn't readable, the path
     * is removed from the list.
     *
     * @return bool True if changes where made, false otherwise
     */
    private function cleanInstalledPaths()
    {
        $changes = false;
        foreach ($this->installedPaths as $key => $path) {
            // This might be a relative path as well
            $alternativePath = realpath($this->getPHPCodeSnifferInstallPath() . DIRECTORY_SEPARATOR . $path);

            if (
                (is_dir($path) === false || is_readable($path) === false) &&
                (is_dir($alternativePath) === false || is_readable($alternativePath) === false)
            ) {
                unset($this->installedPaths[$key]);
                $changes = true;
            }
        }
        return $changes;
    }

    /**
     * Check all installed packages (including the root package) against
     * the installed paths from PHP_CodeSniffer and add the missing ones.
     *
     * @return bool True if changes where made, false otherwise
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function updateInstalledPaths()
    {
        $changes = false;

        $searchPaths = array($this->cwd);
        $codingStandardPackages = $this->getPHPCodingStandardPackages();
        foreach ($codingStandardPackages as $package) {
            $installPath = $this->composer->getInstallationManager()->getInstallPath($package);
            if ($this->filesystem->isAbsolutePath($installPath) === false) {
                $installPath = $this->filesystem->normalizePath(
                    $this->cwd . DIRECTORY_SEPARATOR . $installPath
                );
            }
            $searchPaths[] = $installPath;
        }

        $finder = new Finder();
        $finder->files()
            ->depth('<= ' . $this->getMaxDepth())
            ->depth('>= ' . $this->getMinDepth())
            ->ignoreUnreadableDirs()
            ->ignoreVCS(true)
            ->in($searchPaths)
            ->name('ruleset.xml');

        // Process each found possible ruleset.
        foreach ($finder as $ruleset) {
            $standardsPath = $ruleset->getPath();

            // Pick the directory above the directory containing the standard, unless this is the project root.
            if ($standardsPath !== $this->cwd) {
                $standardsPath = dirname($standardsPath);
            }

            // Use relative paths for local project repositories.
            if ($this->isRunningGlobally() === false) {
                $standardsPath = $this->filesystem->findShortestPath(
                    $this->getPHPCodeSnifferInstallPath(),
                    $standardsPath,
                    true
                );
            }

            // De-duplicate and add when directory is not configured.
            if (in_array($standardsPath, $this->installedPaths, true) === false) {
                $this->installedPaths[] = $standardsPath;
                $changes = true;
            }
        }

        return $changes;
    }

    /**
     * Iterates through Composers' local repository looking for valid Coding
     * Standard packages.
     *
     * If the package is the RootPackage (the one the plugin is installed into),
     * the package is ignored for now since it needs a different install path logic.
     *
     * @return array Composer packages containing coding standard(s)
     */
    private function getPHPCodingStandardPackages()
    {
        $codingStandardPackages = array_filter(
            $this->composer->getRepositoryManager()->getLocalRepository()->getPackages(),
            function (PackageInterface $package) {
                if ($package instanceof AliasPackage) {
                    return false;
                }
                return $package->getType() === Plugin::PACKAGE_TYPE;
            }
        );

        if (
            ! $this->composer->getPackage() instanceof RootPackageInterface
            && $this->composer->getPackage()->getType() === self::PACKAGE_TYPE
        ) {
            $codingStandardPackages[] = $this->composer->getPackage();
        }

        return $codingStandardPackages;
    }

    /**
     * Searches for the installed PHP_CodeSniffer Composer package
     *
     * @param null|string|\Composer\Semver\Constraint\ConstraintInterface $versionConstraint to match against
     *
     * @return PackageInterface|null
     */
    private function getPHPCodeSnifferPackage($versionConstraint = null)
    {
        $packages = $this
            ->composer
            ->getRepositoryManager()
            ->getLocalRepository()
            ->findPackages(self::PACKAGE_NAME, $versionConstraint);

        return array_shift($packages);
    }

    /**
     * Returns the path to the PHP_CodeSniffer package installation location
     *
     * @return string
     */
    private function getPHPCodeSnifferInstallPath()
    {
        return $this->composer->getInstallationManager()->getInstallPath($this->getPHPCodeSnifferPackage());
    }

    /**
     * Simple check if PHP_CodeSniffer is installed.
     *
     * @param null|string|\Composer\Semver\Constraint\ConstraintInterface $versionConstraint to match against
     *
     * @return bool Whether PHP_CodeSniffer is installed
     */
    private function isPHPCodeSnifferInstalled($versionConstraint = null)
    {
        return ($this->getPHPCodeSnifferPackage($versionConstraint) !== null);
    }

    /**
     * Test if composer is running "global"
     * This check kinda dirty, but it is the "Composer Way"
     *
     * @return bool Whether Composer is running "globally"
     *
     * @throws \RuntimeException
     */
    private function isRunningGlobally()
    {
        return ($this->composer->getConfig()->get('home') === $this->cwd);
    }

    /**
     * Determines the maximum search depth when searching for Coding Standards.
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    private function getMaxDepth()
    {
        $maxDepth = 3;

        $extra = $this->composer->getPackage()->getExtra();

        if (array_key_exists(self::KEY_MAX_DEPTH, $extra)) {
            $maxDepth = $extra[self::KEY_MAX_DEPTH];
            $minDepth = $this->getMinDepth();

            if (
                (string) (int) $maxDepth !== (string) $maxDepth /* Must be an integer or cleanly castable to one */
                || $maxDepth <= $minDepth                       /* Larger than the minimum */
                || is_float($maxDepth) === true                 /* Within the boundaries of integer */
            ) {
                $message = vsprintf(
                    self::MESSAGE_ERROR_WRONG_MAX_DEPTH,
                    array(
                        'key' => self::KEY_MAX_DEPTH,
                        'min' => $minDepth,
                        'given' => var_export($maxDepth, true),
                    )
                );

                throw new \InvalidArgumentException($message);
            }
        }

        return (int) $maxDepth;
    }

    /**
     * Returns the minimal search depth for Coding Standard packages.
     *
     * Usually this is 0, unless PHP_CodeSniffer >= 3 is used.
     *
     * @return int
     */
    private function getMinDepth()
    {
        if ($this->isPHPCodeSnifferInstalled('>= 3.0.0') !== true) {
            return 1;
        }
        return 0;
    }
}

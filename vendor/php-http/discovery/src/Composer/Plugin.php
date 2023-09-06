<?php

namespace Http\Discovery\Composer;

use Composer\Composer;
use Composer\DependencyResolver\Pool;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Factory;
use Composer\Installer;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Composer\Package\Locker;
use Composer\Package\Version\VersionParser;
use Composer\Package\Version\VersionSelector;
use Composer\Plugin\PluginInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Repository\RepositorySet;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\Filesystem;
use Http\Discovery\ClassDiscovery;

/**
 * Auto-installs missing implementations.
 *
 * When a dependency requires both this package and one of the supported `*-implementation`
 * virtual packages, this plugin will auto-install a well-known implementation if none is
 * found. The plugin will first look at already installed packages and figure out the
 * preferred implementation to install based on the below stickyness rules (or on the first
 * listed implementation if no rules match.)
 *
 * Don't miss updating src/Strategy/Common*Strategy.php when adding a new supported package.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * Describes, for every supported virtual implementation, which packages
     * provide said implementation and which extra dependencies each package
     * requires to provide the implementation.
     */
    private const PROVIDE_RULES = [
        'php-http/async-client-implementation' => [
            'symfony/http-client:>=6.3' => ['guzzlehttp/promises', 'psr/http-factory-implementation', 'php-http/httplug'],
            'symfony/http-client' => ['guzzlehttp/promises', 'php-http/message-factory', 'psr/http-factory-implementation', 'php-http/httplug'],
            'php-http/guzzle7-adapter' => [],
            'php-http/guzzle6-adapter' => [],
            'php-http/curl-client' => [],
            'php-http/react-adapter' => [],
        ],
        'php-http/client-implementation' => [
            'symfony/http-client:>=6.3' => ['psr/http-factory-implementation', 'php-http/httplug'],
            'symfony/http-client' => ['php-http/message-factory', 'psr/http-factory-implementation', 'php-http/httplug'],
            'php-http/guzzle7-adapter' => [],
            'php-http/guzzle6-adapter' => [],
            'php-http/cakephp-adapter' => [],
            'php-http/curl-client' => [],
            'php-http/react-adapter' => [],
            'php-http/buzz-adapter' => [],
            'php-http/artax-adapter' => [],
            'kriswallsmith/buzz:^1' => [],
        ],
        'psr/http-client-implementation' => [
            'symfony/http-client' => ['psr/http-factory-implementation', 'psr/http-client'],
            'guzzlehttp/guzzle' => [],
            'kriswallsmith/buzz:^1' => [],
        ],
        'psr/http-message-implementation' => [
            'php-http/discovery' => ['psr/http-factory-implementation'],
        ],
        'psr/http-factory-implementation' => [
            'nyholm/psr7' => [],
            'guzzlehttp/psr7:>=2' => [],
            'slim/psr7' => [],
            'laminas/laminas-diactoros' => [],
            'phalcon/cphalcon:^4' => [],
            'http-interop/http-factory-guzzle' => [],
            'http-interop/http-factory-diactoros' => [],
            'http-interop/http-factory-slim' => [],
            'httpsoft/http-message' => [],
        ],
    ];

    /**
     * Describes which package should be preferred on the left side
     * depending on which one is already installed on the right side.
     */
    private const STICKYNESS_RULES = [
        'symfony/http-client' => 'symfony/framework-bundle',
        'php-http/guzzle7-adapter' => 'guzzlehttp/guzzle:^7',
        'php-http/guzzle6-adapter' => 'guzzlehttp/guzzle:^6',
        'php-http/guzzle5-adapter' => 'guzzlehttp/guzzle:^5',
        'php-http/cakephp-adapter' => 'cakephp/cakephp',
        'php-http/react-adapter' => 'react/event-loop',
        'php-http/buzz-adapter' => 'kriswallsmith/buzz:^0.15.1',
        'php-http/artax-adapter' => 'amphp/artax:^3',
        'http-interop/http-factory-guzzle' => 'guzzlehttp/psr7:^1',
        'http-interop/http-factory-slim' => 'slim/slim:^3',
    ];

    private const INTERFACE_MAP = [
        'php-http/async-client-implementation' => [
            'Http\Client\HttpAsyncClient',
        ],
        'php-http/client-implementation' => [
            'Http\Client\HttpClient',
        ],
        'psr/http-client-implementation' => [
            'Psr\Http\Client\ClientInterface',
        ],
        'psr/http-factory-implementation' => [
            'Psr\Http\Message\RequestFactoryInterface',
            'Psr\Http\Message\ResponseFactoryInterface',
            'Psr\Http\Message\ServerRequestFactoryInterface',
            'Psr\Http\Message\StreamFactoryInterface',
            'Psr\Http\Message\UploadedFileFactoryInterface',
            'Psr\Http\Message\UriFactoryInterface',
        ],
    ];

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::PRE_AUTOLOAD_DUMP => 'preAutoloadDump',
            ScriptEvents::POST_UPDATE_CMD => 'postUpdate',
        ];
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    public function postUpdate(Event $event)
    {
        $composer = $event->getComposer();
        $repo = $composer->getRepositoryManager()->getLocalRepository();
        $requires = [
            $composer->getPackage()->getRequires(),
            $composer->getPackage()->getDevRequires(),
        ];
        $pinnedAbstractions = [];
        $pinned = $composer->getPackage()->getExtra()['discovery'] ?? [];
        foreach (self::INTERFACE_MAP as $abstraction => $interfaces) {
            foreach (isset($pinned[$abstraction]) ? [] : $interfaces as $interface) {
                if (!isset($pinned[$interface])) {
                    continue 2;
                }
            }
            $pinnedAbstractions[$abstraction] = true;
        }

        $missingRequires = $this->getMissingRequires($repo, $requires, 'project' === $composer->getPackage()->getType(), $pinnedAbstractions);
        $missingRequires = [
            'require' => array_fill_keys(array_merge([], ...array_values($missingRequires[0])), '*'),
            'require-dev' => array_fill_keys(array_merge([], ...array_values($missingRequires[1])), '*'),
            'remove' => array_fill_keys(array_merge([], ...array_values($missingRequires[2])), '*'),
        ];

        if (!$missingRequires = array_filter($missingRequires)) {
            return;
        }

        $composerJsonContents = file_get_contents(Factory::getComposerFile());
        $this->updateComposerJson($missingRequires, $composer->getConfig()->get('sort-packages'));

        $installer = null;
        // Find the composer installer, hack borrowed from symfony/flex
        foreach (debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT) as $trace) {
            if (isset($trace['object']) && $trace['object'] instanceof Installer) {
                $installer = $trace['object'];
                break;
            }
        }

        if (!$installer) {
            return;
        }

        $event->stopPropagation();

        $dispatcher = $composer->getEventDispatcher();
        $disableScripts = !method_exists($dispatcher, 'setRunScripts') || !((array) $dispatcher)["\0*\0runScripts"];
        $composer = Factory::create($event->getIO(), null, false, $disableScripts);

        /** @var Installer $installer */
        $installer = clone $installer;
        if (method_exists($installer, 'setAudit')) {
            $trace['object']->setAudit(false);
        }
        // we need a clone of the installer to preserve its configuration state but with our own service objects
        $installer->__construct(
            $event->getIO(),
            $composer->getConfig(),
            $composer->getPackage(),
            $composer->getDownloadManager(),
            $composer->getRepositoryManager(),
            $composer->getLocker(),
            $composer->getInstallationManager(),
            $composer->getEventDispatcher(),
            $composer->getAutoloadGenerator()
        );
        if (method_exists($installer, 'setPlatformRequirementFilter')) {
            $installer->setPlatformRequirementFilter(((array) $trace['object'])["\0*\0platformRequirementFilter"]);
        }

        if (0 !== $installer->run()) {
            file_put_contents(Factory::getComposerFile(), $composerJsonContents);

            return;
        }

        $versionSelector = new VersionSelector(ClassDiscovery::safeClassExists(RepositorySet::class) ? new RepositorySet() : new Pool());
        $updateComposerJson = false;

        foreach ($composer->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            foreach (['require', 'require-dev'] as $key) {
                if (!isset($missingRequires[$key][$package->getName()])) {
                    continue;
                }
                $updateComposerJson = true;
                $missingRequires[$key][$package->getName()] = $versionSelector->findRecommendedRequireVersion($package);
            }
        }

        if ($updateComposerJson) {
            $this->updateComposerJson($missingRequires, $composer->getConfig()->get('sort-packages'));
            $this->updateComposerLock($composer, $event->getIO());
        }
    }

    public function getMissingRequires(InstalledRepositoryInterface $repo, array $requires, bool $isProject, array $pinnedAbstractions): array
    {
        $allPackages = [];
        $devPackages = method_exists($repo, 'getDevPackageNames') ? array_fill_keys($repo->getDevPackageNames(), true) : [];

        // One must require "php-http/discovery"
        // to opt-in for auto-installation of virtual package implementations
        if (!isset($requires[0]['php-http/discovery'])) {
            $requires = [[], []];
        }

        foreach ($repo->getPackages() as $package) {
            $allPackages[$package->getName()] = true;

            if (1 < \count($names = $package->getNames(false))) {
                $allPackages += array_fill_keys($names, false);

                if (isset($devPackages[$package->getName()])) {
                    $devPackages += $names;
                }
            }

            if (isset($package->getRequires()['php-http/discovery'])) {
                $requires[(int) isset($devPackages[$package->getName()])] += $package->getRequires();
            }
        }

        $missingRequires = [[], [], []];
        $versionParser = new VersionParser();

        if (ClassDiscovery::safeClassExists(\Phalcon\Http\Message\RequestFactory::class, false)) {
            $missingRequires[0]['psr/http-factory-implementation'] = [];
            $missingRequires[1]['psr/http-factory-implementation'] = [];
        }

        foreach ($requires as $dev => $rules) {
            $abstractions = [];
            $rules = array_intersect_key(self::PROVIDE_RULES, $rules);

            while ($rules) {
                $abstraction = key($rules);

                if (isset($pinnedAbstractions[$abstraction])) {
                    unset($rules[$abstraction]);
                    continue;
                }

                $abstractions[] = $abstraction;

                foreach (array_shift($rules) as $candidate => $deps) {
                    [$candidate, $version] = explode(':', $candidate, 2) + [1 => null];

                    if (!isset($allPackages[$candidate])) {
                        continue;
                    }
                    if (null !== $version && !$repo->findPackage($candidate, $versionParser->parseConstraints($version))) {
                        continue;
                    }
                    if ($isProject && !$dev && isset($devPackages[$candidate])) {
                        $missingRequires[0][$abstraction] = [$candidate];
                        $missingRequires[2][$abstraction] = [$candidate];
                    } else {
                        $missingRequires[$dev][$abstraction] = [];
                    }

                    foreach ($deps as $dep) {
                        if (isset(self::PROVIDE_RULES[$dep])) {
                            $rules[$dep] = self::PROVIDE_RULES[$dep];
                        } elseif (!isset($allPackages[$dep])) {
                            $missingRequires[$dev][$abstraction][] = $dep;
                        } elseif ($isProject && !$dev && isset($devPackages[$dep])) {
                            $missingRequires[0][$abstraction][] = $dep;
                            $missingRequires[2][$abstraction][] = $dep;
                        }
                    }
                    break;
                }
            }

            while ($abstractions) {
                $abstraction = array_shift($abstractions);

                if (isset($missingRequires[$dev][$abstraction])) {
                    continue;
                }
                $candidates = self::PROVIDE_RULES[$abstraction];

                foreach ($candidates as $candidate => $deps) {
                    [$candidate, $version] = explode(':', $candidate, 2) + [1 => null];

                    if (null !== $version && !$repo->findPackage($candidate, $versionParser->parseConstraints($version))) {
                        continue;
                    }
                    if (isset($allPackages[$candidate]) && (!$isProject || $dev || !isset($devPackages[$candidate]))) {
                        continue 2;
                    }
                }

                foreach (array_intersect_key(self::STICKYNESS_RULES, $candidates) as $candidate => $stickyRule) {
                    [$stickyName, $stickyVersion] = explode(':', $stickyRule, 2) + [1 => null];
                    if (!isset($allPackages[$stickyName]) || ($isProject && !$dev && isset($devPackages[$stickyName]))) {
                        continue;
                    }
                    if (null !== $stickyVersion && !$repo->findPackage($stickyName, $versionParser->parseConstraints($stickyVersion))) {
                        continue;
                    }

                    $candidates = [$candidate => $candidates[$candidate]];
                    break;
                }

                $dep = key($candidates);
                [$dep] = explode(':', $dep, 2);
                $missingRequires[$dev][$abstraction] = [$dep];

                if ($isProject && !$dev && isset($devPackages[$dep])) {
                    $missingRequires[2][$abstraction][] = $dep;
                }
            }
        }

        $missingRequires[1] = array_diff_key($missingRequires[1], $missingRequires[0]);

        return $missingRequires;
    }

    public function preAutoloadDump(Event $event)
    {
        $filesystem = new Filesystem();
        // Double realpath() on purpose, see https://bugs.php.net/72738
        $vendorDir = $filesystem->normalizePath(realpath(realpath($event->getComposer()->getConfig()->get('vendor-dir'))));
        $filesystem->ensureDirectoryExists($vendorDir.'/composer');
        $pinned = $event->getComposer()->getPackage()->getExtra()['discovery'] ?? [];
        $candidates = [];

        $allInterfaces = array_merge(...array_values(self::INTERFACE_MAP));
        foreach ($pinned as $abstraction => $class) {
            if (isset(self::INTERFACE_MAP[$abstraction])) {
                $interfaces = self::INTERFACE_MAP[$abstraction];
            } elseif (false !== $k = array_search($abstraction, $allInterfaces, true)) {
                $interfaces = [$allInterfaces[$k]];
            } else {
                throw new \UnexpectedValueException(sprintf('Invalid "extra.discovery" pinned in composer.json: "%s" is not one of ["%s"].', $abstraction, implode('", "', array_keys(self::INTERFACE_MAP))));
            }

            foreach ($interfaces as $interface) {
                $candidates[] = sprintf("case %s: return [['class' => %s]];\n", var_export($interface, true), var_export($class, true));
            }
        }

        $file = $vendorDir.'/composer/GeneratedDiscoveryStrategy.php';

        if (!$candidates) {
            if (file_exists($file)) {
                unlink($file);
            }

            return;
        }

        $candidates = implode('            ', $candidates);
        $code = <<<EOPHP
<?php

namespace Http\Discovery\Strategy;

class GeneratedDiscoveryStrategy implements DiscoveryStrategy
{
    public static function getCandidates(\$type)
    {
        switch (\$type) {
            $candidates
            default: return [];
        }
    }
}

EOPHP
        ;

        if (!file_exists($file) || $code !== file_get_contents($file)) {
            file_put_contents($file, $code);
        }

        $rootPackage = $event->getComposer()->getPackage();
        $autoload = $rootPackage->getAutoload();
        $autoload['classmap'][] = $vendorDir.'/composer/GeneratedDiscoveryStrategy.php';
        $rootPackage->setAutoload($autoload);
    }

    private function updateComposerJson(array $missingRequires, bool $sortPackages)
    {
        $file = Factory::getComposerFile();
        $contents = file_get_contents($file);

        $manipulator = new JsonManipulator($contents);

        foreach ($missingRequires as $key => $packages) {
            foreach ($packages as $package => $constraint) {
                if ('remove' === $key) {
                    $manipulator->removeSubNode('require-dev', $package);
                } else {
                    $manipulator->addLink($key, $package, $constraint, $sortPackages);
                }
            }
        }

        file_put_contents($file, $manipulator->getContents());
    }

    private function updateComposerLock(Composer $composer, IOInterface $io)
    {
        $lock = substr(Factory::getComposerFile(), 0, -4).'lock';
        $composerJson = file_get_contents(Factory::getComposerFile());
        $lockFile = new JsonFile($lock, null, $io);
        $locker = ClassDiscovery::safeClassExists(RepositorySet::class)
            ? new Locker($io, $lockFile, $composer->getInstallationManager(), $composerJson)
            : new Locker($io, $lockFile, $composer->getRepositoryManager(), $composer->getInstallationManager(), $composerJson);
        $lockData = $locker->getLockData();
        $lockData['content-hash'] = Locker::getContentHash($composerJson);
        $lockFile->write($lockData);
    }
}

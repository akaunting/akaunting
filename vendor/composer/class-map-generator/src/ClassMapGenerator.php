<?php declare(strict_types=1);

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * This file was initially based on a version from the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 */

namespace Composer\ClassMapGenerator;

use Composer\Pcre\Preg;
use Symfony\Component\Finder\Finder;
use Composer\IO\IOInterface;

/**
 * ClassMapGenerator
 *
 * @author Gyula Sallai <salla016@gmail.com>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class ClassMapGenerator
{
    /**
     * @var list<string>
     */
    private $extensions;

    /**
     * @var FileList|null
     */
    private $scannedFiles = null;

    /**
     * @var ClassMap
     */
    private $classMap;

    /**
     * @param list<string> $extensions File extensions to scan for classes in the given paths
     */
    public function __construct(array $extensions = ['php', 'inc'])
    {
        $this->extensions = $extensions;
        $this->classMap = new ClassMap;
    }

    /**
     * When calling scanPaths repeatedly with paths that may overlap, calling this will ensure that the same class is never scanned twice
     *
     * You can provide your own FileList instance or use the default one if you pass no argument
     *
     * @return $this
     */
    public function avoidDuplicateScans(FileList $scannedFiles = null): self
    {
        $this->scannedFiles = $scannedFiles ?? new FileList;

        return $this;
    }

    /**
     * Iterate over all files in the given directory searching for classes
     *
     * @param string|\Traversable<\SplFileInfo>|array<\SplFileInfo> $path The path to search in or an array/traversable of SplFileInfo (e.g. symfony/finder instance)
     * @return array<class-string, non-empty-string> A class map array
     *
     * @throws \RuntimeException When the path is neither an existing file nor directory
     */
    public static function createMap($path): array
    {
        $generator = new self();

        $generator->scanPaths($path);

        return $generator->getClassMap()->getMap();
    }

    public function getClassMap(): ClassMap
    {
        return $this->classMap;
    }

    /**
     * Iterate over all files in the given directory searching for classes
     *
     * @param string|\Traversable<\SplFileInfo>|array<\SplFileInfo> $path         The path to search in or an array/traversable of SplFileInfo (e.g. symfony/finder instance)
     * @param non-empty-string|null                                 $excluded     Regex that matches file paths to be excluded from the classmap
     * @param 'classmap'|'psr-0'|'psr-4'                            $autoloadType Optional autoload standard to use mapping rules with the namespace instead of purely doing a classmap
     * @param string|null                                           $namespace    Optional namespace prefix to filter by, only for psr-0/psr-4 autoloading
     *
     * @throws \RuntimeException When the path is neither an existing file nor directory
     */
    public function scanPaths($path, string $excluded = null, string $autoloadType = 'classmap', ?string $namespace = null): void
    {
        if (!in_array($autoloadType, ['psr-0', 'psr-4', 'classmap'], true)) {
            throw new \InvalidArgumentException('$autoloadType must be one of: "psr-0", "psr-4" or "classmap"');
        }

        if ('classmap' !== $autoloadType) {
            if (!is_string($path)) {
                throw new \InvalidArgumentException('$path must be a string when specifying a psr-0 or psr-4 autoload type');
            }
            if (!is_string($namespace)) {
                throw new \InvalidArgumentException('$namespace must be given (even if it is an empty string if you do not want to filter) when specifying a psr-0 or psr-4 autoload type');
            }
            $basePath = $path;
        }

        if (is_string($path)) {
            if (is_file($path)) {
                $path = [new \SplFileInfo($path)];
            } elseif (is_dir($path) || strpos($path, '*') !== false) {
                $path = Finder::create()
                    ->files()
                    ->followLinks()
                    ->name('/\.(?:'.implode('|', array_map('preg_quote', $this->extensions)).')$/')
                    ->in($path);
            } else {
                throw new \RuntimeException(
                    'Could not scan for classes inside "'.$path.'" which does not appear to be a file nor a folder'
                );
            }
        }

        $cwd = realpath(self::getCwd());

        foreach ($path as $file) {
            $filePath = $file->getPathname();
            if (!in_array(pathinfo($filePath, PATHINFO_EXTENSION), $this->extensions, true)) {
                continue;
            }

            if (!self::isAbsolutePath($filePath)) {
                $filePath = $cwd . '/' . $filePath;
                $filePath = self::normalizePath($filePath);
            } else {
                $filePath = Preg::replace('{[\\\\/]{2,}}', '/', $filePath);
            }

            if ('' === $filePath) {
                throw new \LogicException('Got an empty $filePath for '.$file->getPathname());
            }

            $realPath = realpath($filePath);

            // fallback just in case but this really should not happen
            if (false === $realPath) {
                throw new \RuntimeException('realpath of '.$filePath.' failed to resolve, got false');
            }

            // if a list of scanned files is given, avoid scanning twice the same file to save cycles and avoid generating warnings
            // in case a PSR-0/4 declaration follows another more specific one, or a classmap declaration, which covered this file already
            if ($this->scannedFiles !== null && $this->scannedFiles->contains($realPath)) {
                continue;
            }

            // check the realpath of the file against the excluded paths as the path might be a symlink and the excluded path is realpath'd so symlink are resolved
            if (null !== $excluded && Preg::isMatch($excluded, strtr($realPath, '\\', '/'))) {
                continue;
            }
            // check non-realpath of file for directories symlink in project dir
            if (null !== $excluded && Preg::isMatch($excluded, strtr($filePath, '\\', '/'))) {
                continue;
            }

            $classes = PhpFileParser::findClasses($filePath);
            if ('classmap' !== $autoloadType && isset($namespace)) {
                $classes = $this->filterByNamespace($classes, $filePath, $namespace, $autoloadType, $basePath);

                // if no valid class was found in the file then we do not mark it as scanned as it might still be matched by another rule later
                if (\count($classes) > 0 && $this->scannedFiles !== null) {
                    $this->scannedFiles->add($realPath);
                }
            } elseif ($this->scannedFiles !== null) {
                // classmap autoload rules always collect all classes so for these we definitely do not want to scan again
                $this->scannedFiles->add($realPath);
            }

            foreach ($classes as $class) {
                if (!$this->classMap->hasClass($class)) {
                    $this->classMap->addClass($class, $filePath);
                } elseif ($filePath !== $this->classMap->getClassPath($class) && !Preg::isMatch('{/(test|fixture|example|stub)s?/}i', strtr($this->classMap->getClassPath($class).' '.$filePath, '\\', '/'))) {
                    $this->classMap->addAmbiguousClass($class, $filePath);
                }
            }
        }
    }

    /**
     * Remove classes which could not have been loaded by namespace autoloaders
     *
     * @param  array<int, class-string> $classes       found classes in given file
     * @param  string                   $filePath      current file
     * @param  string                   $baseNamespace prefix of given autoload mapping
     * @param  'psr-0'|'psr-4'          $namespaceType
     * @param  string                   $basePath      root directory of given autoload mapping
     * @return array<int, class-string> valid classes
     */
    private function filterByNamespace(array $classes, string $filePath, string $baseNamespace, string $namespaceType, string $basePath): array
    {
        $validClasses = [];
        $rejectedClasses = [];

        $realSubPath = substr($filePath, strlen($basePath) + 1);
        $dotPosition = strrpos($realSubPath, '.');
        $realSubPath = substr($realSubPath, 0, $dotPosition === false ? PHP_INT_MAX : $dotPosition);

        foreach ($classes as $class) {
            // silently skip if ns doesn't have common root
            if ('' !== $baseNamespace && 0 !== strpos($class, $baseNamespace)) {
                continue;
            }
            // transform class name to file path and validate
            if ('psr-0' === $namespaceType) {
                $namespaceLength = strrpos($class, '\\');
                if (false !== $namespaceLength) {
                    $namespace = substr($class, 0, $namespaceLength + 1);
                    $className = substr($class, $namespaceLength + 1);
                    $subPath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace)
                        . str_replace('_', DIRECTORY_SEPARATOR, $className);
                } else {
                    $subPath = str_replace('_', DIRECTORY_SEPARATOR, $class);
                }
            } elseif ('psr-4' === $namespaceType) {
                $subNamespace = ('' !== $baseNamespace) ? substr($class, strlen($baseNamespace)) : $class;
                $subPath = str_replace('\\', DIRECTORY_SEPARATOR, $subNamespace);
            } else {
                throw new \InvalidArgumentException('$namespaceType must be "psr-0" or "psr-4"');
            }
            if ($subPath === $realSubPath) {
                $validClasses[] = $class;
            } else {
                $rejectedClasses[] = $class;
            }
        }
        // warn only if no valid classes, else silently skip invalid
        if (\count($validClasses) === 0) {
            foreach ($rejectedClasses as $class) {
                $this->classMap->addPsrViolation("Class $class located in ".Preg::replace('{^'.preg_quote(self::getCwd()).'}', '.', $filePath, 1)." does not comply with $namespaceType autoloading standard. Skipping.");
            }

            return [];
        }

        return $validClasses;
    }

    /**
     * Checks if the given path is absolute
     *
     * @see Composer\Util\Filesystem::isAbsolutePath
     *
     * @param  string $path
     * @return bool
     */
    private static function isAbsolutePath(string $path)
    {
        return strpos($path, '/') === 0 || substr($path, 1, 1) === ':' || strpos($path, '\\\\') === 0;
    }

    /**
     * Normalize a path. This replaces backslashes with slashes, removes ending
     * slash and collapses redundant separators and up-level references.
     *
     * @see Composer\Util\Filesystem::normalizePath
     *
     * @param  string $path Path to the file or directory
     * @return string
     */
    private static function normalizePath(string $path)
    {
        $parts = [];
        $path = strtr($path, '\\', '/');
        $prefix = '';
        $absolute = '';

        // extract windows UNC paths e.g. \\foo\bar
        if (strpos($path, '//') === 0 && \strlen($path) > 2) {
            $absolute = '//';
            $path = substr($path, 2);
        }

        // extract a prefix being a protocol://, protocol:, protocol://drive: or simply drive:
        if (Preg::isMatchStrictGroups('{^( [0-9a-z]{2,}+: (?: // (?: [a-z]: )? )? | [a-z]: )}ix', $path, $match)) {
            $prefix = $match[1];
            $path = substr($path, \strlen($prefix));
        }

        if (strpos($path, '/') === 0) {
            $absolute = '/';
            $path = substr($path, 1);
        }

        $up = false;
        foreach (explode('/', $path) as $chunk) {
            if ('..' === $chunk && (\strlen($absolute) > 0 || $up)) {
                array_pop($parts);
                $up = !(\count($parts) === 0 || '..' === end($parts));
            } elseif ('.' !== $chunk && '' !== $chunk) {
                $parts[] = $chunk;
                $up = '..' !== $chunk;
            }
        }

        // ensure c: is normalized to C:
        $prefix = Preg::replaceCallback('{(?:^|://)[a-z]:$}i', function (array $m) { return strtoupper((string) $m[0]); }, $prefix);

        return $prefix.$absolute.implode('/', $parts);
    }

    /**
     * @see Composer\Util\Platform::getCwd
     */
    private static function getCwd(): string
    {
        $cwd = getcwd();

        if (false === $cwd) {
            throw new \RuntimeException('Could not determine the current working directory');
        }

        return $cwd;
    }
}

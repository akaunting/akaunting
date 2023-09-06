<?php declare(strict_types=1);

namespace Wnx\LaravelStats;

use Exception;
use SplFileInfo;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

class ClassesFinder
{
    /**
     * Find PHP Files on filesystem and require them.
     * We need to use ob_* functions to ensure that
     * loaded files do not output anything.
     */
    public function findAndLoadClasses(): Collection
    {
        ob_start();

        $this->findFilesInProjectPath()
            ->each(function (SplFileInfo $file) {
                try {
                    // Files that look like to be Pest Tests are ignored as we currently don't support them.
                    if ($this->isMostLikelyPestTest($file)) {
                        return true;
                    }
                    require_once $file->getRealPath();
                } catch (Exception $e) {
                    //
                }
            });

        ob_end_clean();

        return collect(get_declared_classes())
            ->reject(function (string $className) {
                return Str::startsWith($className, ['SwooleLibrary']);
            });
    }

    /**
     * Find PHP Files which should be analyzed.
     */
    protected function findFilesInProjectPath(): Collection
    {
        $excludes = collect(config('stats.exclude', []));

        $files = (new Finder)->files()
            ->in(config('stats.paths', []))
            ->name('*.php');

        return collect($files)
            ->reject(function ($file) use ($excludes) {
                return $this->isExcluded($file, $excludes);
            });
    }

    /**
     * Determine if a file has been defined in the exclude configuration.
     */
    protected function isExcluded(SplFileInfo $file, Collection $excludes): bool
    {
        return $excludes->contains(function ($exclude) use ($file) {
            return Str::startsWith($file->getPathname(), $exclude);
        });
    }

    /**
     * Determine if a file is a Pest Test.
     * Pest Tess are currently not supported as requiring them will throw an exception.
     */
    protected function isMostLikelyPestTest(SplFileInfo $file): bool
    {
        if (str_ends_with($file->getRealPath(), 'Pest.php')) {
            return true;
        }

        // If the file path does not contain "test" or "Test", then it's probably not a Pest Test.
        if (! str_contains($file->getRealPath(), 'test') && ! str_contains($file->getRealPath(), 'Test')) {
            return false;
        }

        $fileContent = file_get_contents($file->getRealPath());

        // Check if file contains "class $name" syntax.
        // If it does, it's probably a normal PhpUnit Test.
        if (preg_match('/class\s/', $fileContent)) {
            return false;
        }

        // Check if file contains method calls to prominent Pest functions.
        // If it does, it's probably a Pest Test.
        $methodNames = implode('|', [
            'describe',
            'test',
            'it',
            'beforeEach',
            'afterEach',
            'beforeAll',
            'afterAll',
        ]);

        if (preg_match("/$methodNames\s*\(/", $fileContent)) {
            return true;
        }

        return false;
    }
}

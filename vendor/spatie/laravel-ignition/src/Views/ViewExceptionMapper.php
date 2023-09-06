<?php

namespace Spatie\LaravelIgnition\Views;

use Exception;
use Illuminate\Contracts\View\Engine;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\ViewException;
use ReflectionClass;
use ReflectionProperty;
use Spatie\Ignition\Contracts\ProvidesSolution;
use Spatie\LaravelIgnition\Exceptions\ViewException as IgnitionViewException;
use Spatie\LaravelIgnition\Exceptions\ViewExceptionWithSolution;
use Throwable;

class ViewExceptionMapper
{
    protected Engine $compilerEngine;
    protected BladeSourceMapCompiler $bladeSourceMapCompiler;
    protected array $knownPaths;

    public function __construct(BladeSourceMapCompiler $bladeSourceMapCompiler)
    {
        $resolver = app('view.engine.resolver');

        $this->compilerEngine = $resolver->resolve('blade');

        $this->bladeSourceMapCompiler = $bladeSourceMapCompiler;
    }

    public function map(ViewException $viewException): IgnitionViewException
    {
        $baseException = $this->getRealException($viewException);

        if ($baseException instanceof IgnitionViewException) {
            return $baseException;
        }

        preg_match('/\(View: (?P<path>.*?)\)/', $viewException->getMessage(), $matches);

        $compiledViewPath = $matches['path'];

        $exception = $this->createException($baseException);

        if ($baseException instanceof ProvidesSolution) {
            /** @var ViewExceptionWithSolution $exception */
            $exception->setSolution($baseException->getSolution());
        }

        $this->modifyViewsInTrace($exception);

        $exception->setView($compiledViewPath);
        $exception->setViewData($this->getViewData($exception));

        return $exception;
    }

    protected function createException(Throwable $baseException): IgnitionViewException
    {
        $viewExceptionClass = $baseException instanceof ProvidesSolution
            ? ViewExceptionWithSolution::class
            : IgnitionViewException::class;

        $viewFile = $this->findCompiledView($baseException->getFile());
        $file = $viewFile ?? $baseException->getFile();
        $line = $viewFile ? $this->getBladeLineNumber($file, $baseException->getLine()) : $baseException->getLine();

        return new $viewExceptionClass(
            $baseException->getMessage(),
            0,
            1,
            $file,
            $line,
            $baseException
        );
    }

    protected function modifyViewsInTrace(IgnitionViewException $exception): void
    {
        $trace = Collection::make($exception->getPrevious()->getTrace())
            ->map(function ($trace) {
                if ($originalPath = $this->findCompiledView(Arr::get($trace, 'file', ''))) {
                    $trace['file'] = $originalPath;
                    $trace['line'] = $this->getBladeLineNumber($trace['file'], $trace['line']);
                }

                return $trace;
            })->toArray();

        $traceProperty = new ReflectionProperty('Exception', 'trace');
        $traceProperty->setAccessible(true);
        $traceProperty->setValue($exception, $trace);
    }

    /**
     * Look at the previous exceptions to find the original exception.
     * This is usually the first Exception that is not a ViewException.
     */
    protected function getRealException(Throwable $exception): Throwable
    {
        $rootException = $exception->getPrevious() ?? $exception;

        while ($rootException instanceof ViewException && $rootException->getPrevious()) {
            $rootException = $rootException->getPrevious();
        }

        return $rootException;
    }

    protected function findCompiledView(string $compiledPath): ?string
    {
        $this->knownPaths ??= $this->getKnownPaths();

        return $this->knownPaths[$compiledPath] ?? null;
    }

    protected function getKnownPaths(): array
    {
        $compilerEngineReflection = new ReflectionClass($this->compilerEngine);

        if (! $compilerEngineReflection->hasProperty('lastCompiled') && $compilerEngineReflection->hasProperty('engine')) {
            $compilerEngine = $compilerEngineReflection->getProperty('engine');
            $compilerEngine->setAccessible(true);
            $compilerEngine = $compilerEngine->getValue($this->compilerEngine);
            $lastCompiled = new ReflectionProperty($compilerEngine, 'lastCompiled');
            $lastCompiled->setAccessible(true);
            $lastCompiled = $lastCompiled->getValue($compilerEngine);
        } else {
            $lastCompiled = $compilerEngineReflection->getProperty('lastCompiled');
            $lastCompiled->setAccessible(true);
            $lastCompiled = $lastCompiled->getValue($this->compilerEngine);
        }

        $knownPaths = [];
        foreach ($lastCompiled as $lastCompiledPath) {
            $compiledPath = $this->compilerEngine->getCompiler()->getCompiledPath($lastCompiledPath);

            $knownPaths[realpath($compiledPath ?? $lastCompiledPath)] = realpath($lastCompiledPath);
        }

        return $knownPaths;
    }

    protected function getBladeLineNumber(string $view, int $compiledLineNumber): int
    {
        return $this->bladeSourceMapCompiler->detectLineNumber($view, $compiledLineNumber);
    }

    protected function getViewData(Throwable $exception): array
    {
        foreach ($exception->getTrace() as $frame) {
            if (Arr::get($frame, 'class') === PhpEngine::class) {
                $data = Arr::get($frame, 'args.1', []);

                return $this->filterViewData($data);
            }
        }

        return [];
    }

    protected function filterViewData(array $data): array
    {
        // By default, Laravel views get two data keys:
        // __env and app. We try to filter them out.
        return array_filter($data, function ($value, $key) {
            if ($key === 'app') {
                return ! $value instanceof Application;
            }

            return $key !== '__env';
        }, ARRAY_FILTER_USE_BOTH);
    }
}

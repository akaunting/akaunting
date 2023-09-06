<?php

declare(strict_types=1);

namespace NunoMaduro\Collision\Adapters\Laravel;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use NunoMaduro\Collision\Provider;
use Symfony\Component\Console\Exception\ExceptionInterface as SymfonyConsoleExceptionInterface;
use Throwable;

/**
 * @internal
 */
final class ExceptionHandler implements ExceptionHandlerContract
{
    /**
     * Holds an instance of the application exception handler.
     *
     * @var \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected $appExceptionHandler;

    /**
     * Holds an instance of the container.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Creates a new instance of the ExceptionHandler.
     */
    public function __construct(Container $container, ExceptionHandlerContract $appExceptionHandler)
    {
        $this->container = $container;
        $this->appExceptionHandler = $appExceptionHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function report(Throwable $e)
    {
        $this->appExceptionHandler->report($e);
    }

    /**
     * {@inheritdoc}
     */
    public function render($request, Throwable $e)
    {
        return $this->appExceptionHandler->render($request, $e);
    }

    /**
     * {@inheritdoc}
     */
    public function renderForConsole($output, Throwable $e)
    {
        if ($e instanceof SymfonyConsoleExceptionInterface) {
            $this->appExceptionHandler->renderForConsole($output, $e);
        } else {
            /** @var Provider $provider */
            $provider = $this->container->make(Provider::class);

            $handler = $provider->register()
                ->getHandler()
                ->setOutput($output);

            $handler->setInspector((new Inspector($e)));

            $handler->handle();
        }
    }

    /**
     * Determine if the exception should be reported.
     *
     * @return bool
     */
    public function shouldReport(Throwable $e)
    {
        return $this->appExceptionHandler->shouldReport($e);
    }

    /**
     * Register a renderable callback.
     *
     * @return $this
     */
    public function reportable(callable $reportUsing)
    {
        $this->appExceptionHandler->reportable($reportUsing); // @phpstan-ignore-line
    }

    /**
     * Register a reportable callback.
     *
     * @return \Illuminate\Foundation\Exceptions\ReportableHandler
     */
    public function renderable(callable $renderUsing)
    {
        $this->appExceptionHandler->renderable($renderUsing); // @phpstan-ignore-line
    }
}

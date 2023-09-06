<?php

namespace Bugsnag;

use Exception;
use Throwable;

class Handler
{
    /**
     * The client instance.
     *
     * @var \Bugsnag\Client
     */
    protected $client;

    /**
     * The previously registered error handler.
     *
     * @var callable|null
     */
    protected $previousErrorHandler;

    /**
     * The previously registered exception handler.
     *
     * @var callable|null
     */
    protected $previousExceptionHandler;

    /**
     * A bit of reserved memory to ensure we are able to increase the memory
     * limit on an OOM.
     *
     * We can't reserve all of the memory that we need to send OOM reports
     * because this would have a big overhead on every request, instead of just
     * on shutdown in requests with errors.
     *
     * @var string|null
     */
    private $reservedMemory;

    /**
     * A regex that matches PHP OOM errors.
     *
     * @var string
     */
    private $oomRegex = '/^Allowed memory size of (\d+) bytes exhausted \(tried to allocate \d+ bytes\)/';

    /**
     * Whether the shutdown handler will run.
     *
     * This is used to disable the shutdown handler in order to avoid double
     * reporting exceptions when trying to run the native PHP exception handler.
     *
     * @var bool
     */
    private static $enableShutdownHandler = true;

    /**
     * Register our handlers.
     *
     * @param \Bugsnag\Client|string|null $client client instance or api key
     *
     * @return static
     */
    public static function register($client = null)
    {
        if (!$client instanceof Client) {
            $client = Client::make($client);
        }

        // @phpstan-ignore-next-line
        $handler = new static($client);
        $handler->registerBugsnagHandlers(true);

        return $handler;
    }

    /**
     * Register our handlers and preserve those previously registered.
     *
     * @param \Bugsnag\Client|string|null $client client instance or api key
     *
     * @return static
     *
     * @deprecated Use {@see Handler::register} instead.
     */
    public static function registerWithPrevious($client = null)
    {
        return self::register($client);
    }

    /**
     * Register our handlers, optionally saving those previously registered.
     *
     * @param bool $callPrevious whether or not to call the previous handlers
     *
     * @return void
     */
    protected function registerBugsnagHandlers($callPrevious)
    {
        $this->registerErrorHandler($callPrevious);
        $this->registerExceptionHandler($callPrevious);
        $this->registerShutdownHandler();
    }

    /**
     * Register the bugsnag error handler and save the returned value.
     *
     * @param bool $callPrevious whether or not to call the previous handler
     *
     * @return void
     */
    public function registerErrorHandler($callPrevious)
    {
        $previous = set_error_handler([$this, 'errorHandler']);

        if ($callPrevious) {
            $this->previousErrorHandler = $previous;
        }
    }

    /**
     * Register the bugsnag exception handler and save the returned value.
     *
     * @param bool $callPrevious whether or not to call the previous handler
     *
     * @return void
     */
    public function registerExceptionHandler($callPrevious)
    {
        $previous = set_exception_handler([$this, 'exceptionHandler']);

        if (!$callPrevious) {
            return;
        }

        // If there is no previous exception handler, we create one that re-raises
        // the exception in order to trigger PHP's default exception handler
        if (!is_callable($previous)) {
            $previous = static function ($throwable) {
                throw $throwable;
            };
        }

        $this->previousExceptionHandler = $previous;
    }

    /**
     * Register our shutdown handler.
     *
     * PHP will call shutdown functions in the order they were registered.
     *
     * @return void
     */
    public function registerShutdownHandler()
    {
        // Reserve some memory that we can free in the shutdown handler
        $this->reservedMemory = str_repeat(' ', 1024 * 32);

        register_shutdown_function([$this, 'shutdownHandler']);
    }

    /**
     * Create a new exception handler instance.
     *
     * @param \Bugsnag\Client $client
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Exception handler callback.
     *
     * @param Throwable $throwable the exception was was thrown
     *
     * @return void
     */
    public function exceptionHandler($throwable)
    {
        $this->notifyThrowable($throwable);

        // If we don't have a previous handler to call, there's nothing left to do
        if (!$this->previousExceptionHandler) {
            return;
        }

        // These empty catches exist to set $exceptionFromPreviousHandler — we
        // support both PHP 5 & 7 so can't have a single Throwable catch
        try {
            call_user_func($this->previousExceptionHandler, $throwable);

            return;
        } catch (Throwable $exceptionFromPreviousHandler) {
            // TODO: if we drop support for PHP 5, we can remove this catch, which
            //       fixes the PHPStan issue here
            // @phpstan-ignore-next-line
        } catch (Exception $exceptionFromPreviousHandler) {
        }

        // If the previous handler threw the same exception that we are currently
        // handling then it's trying to force PHP's native exception handler to run
        // In this case we disable our shutdown handler (to avoid reporting it
        // twice) and re-throw the exception
        if ($throwable === $exceptionFromPreviousHandler) {
            self::$enableShutdownHandler = false;

            throw $throwable;
        }

        // The previous handler raised a new exception so send a notification
        // for it too. We don't want the previous handler to run for this
        // exception, as it may keep throwing new exceptions
        $this->notifyThrowable($exceptionFromPreviousHandler);
    }

    /**
     * Send a notification for the given throwable.
     *
     * @param Throwable $throwable
     *
     * @return void
     */
    private function notifyThrowable($throwable)
    {
        $report = Report::fromPHPThrowable(
            $this->client->getConfig(),
            $throwable
        );

        $report->setSeverity('error');
        $report->setUnhandled(true);
        $report->setSeverityReason(['type' => 'unhandledException']);

        $this->client->notify($report);
    }

    /**
     * Error handler callback.
     *
     * @param int    $errno   the level of the error raised
     * @param string $errstr  the error message
     * @param string $errfile the filename that the error was raised in
     * @param int    $errline the line number the error was raised at
     *
     * @return bool
     */
    public function errorHandler($errno, $errstr, $errfile = '', $errline = 0)
    {
        if (!$this->client->getConfig()->shouldIgnoreErrorCode($errno)) {
            $report = Report::fromPHPError(
                $this->client->getConfig(),
                $errno,
                $errstr,
                $errfile,
                $errline,
                false
            );

            $report->setUnhandled(true);
            $report->setSeverityReason([
                'type' => 'unhandledError',
                'attributes' => [
                    'errorType' => ErrorTypes::getName($errno),
                ],
            ]);

            $this->client->notify($report);
        }

        if ($this->previousErrorHandler) {
            return call_user_func(
                $this->previousErrorHandler,
                $errno,
                $errstr,
                $errfile,
                $errline
            );
        }

        return false;
    }

    /**
     * Shutdown handler callback.
     *
     * @return void
     */
    public function shutdownHandler()
    {
        // Free the reserved memory to give ourselves some room to work
        $this->reservedMemory = null;

        // If we're disabled, do nothing. This avoids reporting twice if the
        // exception handler is forcing the native PHP handler to run
        if (!self::$enableShutdownHandler) {
            return;
        }

        $lastError = error_get_last();

        // If this is an OOM and memory increase is enabled, bump the memory
        // limit so we can report it
        if ($lastError !== null
            && $this->client->getMemoryLimitIncrease() !== null
            && preg_match($this->oomRegex, $lastError['message'], $matches) === 1
        ) {
            $currentMemoryLimit = (int) $matches[1];
            $newMemoryLimit = $currentMemoryLimit + $this->client->getMemoryLimitIncrease();

            ini_set('memory_limit', (string) $newMemoryLimit);
        }

        // Check if a fatal error caused this shutdown
        if (!is_null($lastError) && ErrorTypes::isFatal($lastError['type']) && !$this->client->getConfig()->shouldIgnoreErrorCode($lastError['type'])) {
            $report = Report::fromPHPError(
                $this->client->getConfig(),
                $lastError['type'],
                $lastError['message'],
                $lastError['file'],
                $lastError['line'],
                true
            );

            $report->setSeverity('error');
            $report->setUnhandled(true);
            $report->setSeverityReason([
                'type' => 'unhandledException',
            ]);

            $this->client->notify($report);
        }

        // Flush any buffered errors
        $this->client->flush();
    }
}

<?php

declare(strict_types=1);

namespace Sentry;

use Sentry\Exception\FatalErrorException;
use Sentry\Exception\SilencedErrorException;

/**
 * This class implements a simple error handler that catches all configured
 * error types and relays them to all configured listeners. Registering this
 * error handler more than once is not supported and will lead to nasty
 * problems. The code is based on the Symfony ErrorHandler component.
 *
 * @psalm-import-type StacktraceFrame from FrameBuilder
 */
final class ErrorHandler
{
    /**
     * The default amount of bytes of memory to reserve for the fatal error handler.
     *
     * @internal
     */
    public const DEFAULT_RESERVED_MEMORY_SIZE = 10240;

    /**
     * The fatal error types that cannot be silenced using the @ operator in PHP 8+.
     */
    private const PHP8_UNSILENCEABLE_FATAL_ERRORS = \E_ERROR | \E_PARSE | \E_CORE_ERROR | \E_COMPILE_ERROR | \E_USER_ERROR | \E_RECOVERABLE_ERROR;

    /**
     * @var self|null The current registered handler (this class is a singleton)
     */
    private static $handlerInstance;

    /**
     * @var callable[] List of listeners that will act on each captured error
     *
     * @psalm-var (callable(\ErrorException): void)[]
     */
    private $errorListeners = [];

    /**
     * @var callable[] List of listeners that will act of each captured fatal error
     *
     * @psalm-var (callable(FatalErrorException): void)[]
     */
    private $fatalErrorListeners = [];

    /**
     * @var callable[] List of listeners that will act on each captured exception
     *
     * @psalm-var (callable(\Throwable): void)[]
     */
    private $exceptionListeners = [];

    /**
     * @var \ReflectionProperty A reflection cached instance that points to the
     *                          trace property of the exception objects
     */
    private $exceptionReflection;

    /**
     * @var callable|null The previous error handler, if any
     */
    private $previousErrorHandler;

    /**
     * @var callable|null The previous exception handler, if any
     *
     * @psalm-var null|callable(\Throwable): void
     */
    private $previousExceptionHandler;

    /**
     * @var bool Whether the error handler has been registered
     */
    private $isErrorHandlerRegistered = false;

    /**
     * @var bool Whether the exception handler has been registered
     */
    private $isExceptionHandlerRegistered = false;

    /**
     * @var bool Whether the fatal error handler has been registered
     */
    private $isFatalErrorHandlerRegistered = false;

    /**
     * @var string|null A portion of pre-allocated memory data that will be reclaimed
     *                  in case a fatal error occurs to handle it
     */
    private static $reservedMemory;

    /**
     * @var string[] List of error levels and their description
     */
    private const ERROR_LEVELS_DESCRIPTION = [
        \E_DEPRECATED => 'Deprecated',
        \E_USER_DEPRECATED => 'User Deprecated',
        \E_NOTICE => 'Notice',
        \E_USER_NOTICE => 'User Notice',
        \E_STRICT => 'Runtime Notice',
        \E_WARNING => 'Warning',
        \E_USER_WARNING => 'User Warning',
        \E_COMPILE_WARNING => 'Compile Warning',
        \E_CORE_WARNING => 'Core Warning',
        \E_USER_ERROR => 'User Error',
        \E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
        \E_COMPILE_ERROR => 'Compile Error',
        \E_PARSE => 'Parse Error',
        \E_ERROR => 'Error',
        \E_CORE_ERROR => 'Core Error',
    ];

    /**
     * Constructor.
     *
     * @throws \ReflectionException If hooking into the \Exception class to
     *                              make the `trace` property accessible fails
     */
    private function __construct()
    {
        $this->exceptionReflection = new \ReflectionProperty(\Exception::class, 'trace');
        $this->exceptionReflection->setAccessible(true);
    }

    /**
     * Registers the error handler once and returns its instance.
     */
    public static function registerOnceErrorHandler(): self
    {
        if (null === self::$handlerInstance) {
            self::$handlerInstance = new self();
        }

        if (self::$handlerInstance->isErrorHandlerRegistered) {
            return self::$handlerInstance;
        }

        $errorHandlerCallback = \Closure::fromCallable([self::$handlerInstance, 'handleError']);

        self::$handlerInstance->isErrorHandlerRegistered = true;
        self::$handlerInstance->previousErrorHandler = set_error_handler($errorHandlerCallback);

        if (null === self::$handlerInstance->previousErrorHandler) {
            restore_error_handler();

            // Specifying the error types caught by the error handler with the
            // first call to the set_error_handler method would cause the PHP
            // bug https://bugs.php.net/63206 if the handler is not the first
            // one in the chain of handlers
            set_error_handler($errorHandlerCallback, \E_ALL);
        }

        return self::$handlerInstance;
    }

    /**
     * Registers the fatal error handler and reserves a certain amount of memory
     * that will be reclaimed to handle the errors (to prevent out of memory
     * issues while handling them) and returns its instance.
     *
     * @param int $reservedMemorySize The amount of memory to reserve for the fatal
     *                                error handler expressed in bytes
     */
    public static function registerOnceFatalErrorHandler(int $reservedMemorySize = self::DEFAULT_RESERVED_MEMORY_SIZE): self
    {
        if ($reservedMemorySize <= 0) {
            throw new \InvalidArgumentException('The $reservedMemorySize argument must be greater than 0.');
        }

        if (null === self::$handlerInstance) {
            self::$handlerInstance = new self();
        }

        if (self::$handlerInstance->isFatalErrorHandlerRegistered) {
            return self::$handlerInstance;
        }

        self::$handlerInstance->isFatalErrorHandlerRegistered = true;
        self::$reservedMemory = str_repeat('x', $reservedMemorySize);

        register_shutdown_function(\Closure::fromCallable([self::$handlerInstance, 'handleFatalError']));

        return self::$handlerInstance;
    }

    /**
     * Registers the exception handler, effectively replacing the current one
     * and returns its instance. The previous one will be saved anyway and
     * called when appropriate.
     */
    public static function registerOnceExceptionHandler(): self
    {
        if (null === self::$handlerInstance) {
            self::$handlerInstance = new self();
        }

        if (self::$handlerInstance->isExceptionHandlerRegistered) {
            return self::$handlerInstance;
        }

        self::$handlerInstance->isExceptionHandlerRegistered = true;
        self::$handlerInstance->previousExceptionHandler = set_exception_handler(\Closure::fromCallable([self::$handlerInstance, 'handleException']));

        return self::$handlerInstance;
    }

    /**
     * Adds a listener to the current error handler that will be called every
     * time an error is captured.
     *
     * @param callable $listener A callable that will act as a listener
     *                           and that must accept a single argument
     *                           of type \ErrorException
     *
     * @psalm-param callable(\ErrorException): void $listener
     */
    public function addErrorHandlerListener(callable $listener): void
    {
        $this->errorListeners[] = $listener;
    }

    /**
     * Adds a listener to the current error handler that will be called every
     * time a fatal error handler is captured.
     *
     * @param callable $listener A callable that will act as a listener
     *                           and that must accept a single argument
     *                           of type \Sentry\Exception\FatalErrorException
     *
     * @psalm-param callable(FatalErrorException): void $listener
     */
    public function addFatalErrorHandlerListener(callable $listener): void
    {
        $this->fatalErrorListeners[] = $listener;
    }

    /**
     * Adds a listener to the current error handler that will be called every
     * time an exception is captured.
     *
     * @param callable $listener A callable that will act as a listener
     *                           and that must accept a single argument
     *                           of type \Throwable
     *
     * @psalm-param callable(\Throwable): void $listener
     */
    public function addExceptionHandlerListener(callable $listener): void
    {
        $this->exceptionListeners[] = $listener;
    }

    /**
     * Handles errors by capturing them through the client according to the
     * configured bit field.
     *
     * @param int                       $level      The level of the error raised, represented by
     *                                              one of the E_* constants
     * @param string                    $message    The error message
     * @param string                    $file       The filename the error was raised in
     * @param int                       $line       The line number the error was raised at
     * @param array<string, mixed>|null $errcontext The error context (deprecated since PHP 7.2)
     *
     * @return bool If the function returns `false` then the PHP native error
     *              handler will be called
     *
     * @throws \Throwable
     */
    private function handleError(int $level, string $message, string $file, int $line, ?array $errcontext = []): bool
    {
        $isSilencedError = 0 === error_reporting();

        if (\PHP_MAJOR_VERSION >= 8) {
            // Starting from PHP8, when a silenced error occurs the `error_reporting()`
            // function will return a bitmask of fatal errors that are unsilenceable.
            // If by subtracting from this value those errors the result is 0, we can
            // conclude that the error was silenced.
            $isSilencedError = 0 === (error_reporting() & ~self::PHP8_UNSILENCEABLE_FATAL_ERRORS);

            // However, starting from PHP8 some fatal errors are unsilenceable,
            // so we have to check for them to avoid reporting any of them as
            // silenced instead
            if ($level === (self::PHP8_UNSILENCEABLE_FATAL_ERRORS & $level)) {
                $isSilencedError = false;
            }
        }

        if ($isSilencedError) {
            $errorAsException = new SilencedErrorException(self::ERROR_LEVELS_DESCRIPTION[$level] . ': ' . $message, 0, $level, $file, $line);
        } else {
            $errorAsException = new \ErrorException(self::ERROR_LEVELS_DESCRIPTION[$level] . ': ' . $message, 0, $level, $file, $line);
        }

        $backtrace = $this->cleanBacktraceFromErrorHandlerFrames($errorAsException->getTrace(), $errorAsException->getFile(), $errorAsException->getLine());

        $this->exceptionReflection->setValue($errorAsException, $backtrace);

        $this->invokeListeners($this->errorListeners, $errorAsException);

        if (null !== $this->previousErrorHandler) {
            return false !== ($this->previousErrorHandler)($level, $message, $file, $line, $errcontext);
        }

        return false;
    }

    /**
     * Tries to handle a fatal error if any and relay them to the listeners.
     * It only tries to do this if we still have some reserved memory at
     * disposal. This method is used as callback of a shutdown function.
     */
    private function handleFatalError(): void
    {
        // If there is not enough memory that can be used to handle the error
        // do nothing
        if (null === self::$reservedMemory) {
            return;
        }

        self::$reservedMemory = null;
        $error = error_get_last();

        if (!empty($error) && $error['type'] & (\E_ERROR | \E_PARSE | \E_CORE_ERROR | \E_CORE_WARNING | \E_COMPILE_ERROR | \E_COMPILE_WARNING)) {
            $errorAsException = new FatalErrorException(self::ERROR_LEVELS_DESCRIPTION[$error['type']] . ': ' . $error['message'], 0, $error['type'], $error['file'], $error['line']);

            $this->exceptionReflection->setValue($errorAsException, []);

            $this->invokeListeners($this->fatalErrorListeners, $errorAsException);
        }
    }

    /**
     * Handles the given exception by passing it to all the listeners,
     * then forwarding it to another handler.
     *
     * @param \Throwable $exception The exception to handle
     *
     * @throws \Throwable
     */
    private function handleException(\Throwable $exception): void
    {
        $this->invokeListeners($this->exceptionListeners, $exception);

        $previousExceptionHandlerException = $exception;

        // Unset the previous exception handler to prevent infinite loop in case
        // we need to handle an exception thrown from it
        $previousExceptionHandler = $this->previousExceptionHandler;
        $this->previousExceptionHandler = null;

        try {
            if (null !== $previousExceptionHandler) {
                $previousExceptionHandler($exception);

                return;
            }
        } catch (\Throwable $previousExceptionHandlerException) {
            // This `catch` statement is here to forcefully override the
            // $previousExceptionHandlerException variable with the exception
            // we just caught
        }

        // If the instance of the exception we're handling is the same as the one
        // caught from the previous exception handler then we give it back to the
        // native PHP handler to prevent an infinite loop
        if ($exception === $previousExceptionHandlerException) {
            // Disable the fatal error handler or the error will be reported twice
            self::$reservedMemory = null;

            throw $exception;
        }

        $this->handleException($previousExceptionHandlerException);
    }

    /**
     * Cleans and returns the backtrace without the first frames that belong to
     * this error handler.
     *
     * @param array<int, array<string, mixed>> $backtrace The backtrace to clear
     * @param string                           $file      The filename the backtrace was raised in
     * @param int                              $line      The line number the backtrace was raised at
     *
     * @return array<int, mixed>
     *
     * @psalm-param list<StacktraceFrame> $backtrace
     */
    private function cleanBacktraceFromErrorHandlerFrames(array $backtrace, string $file, int $line): array
    {
        $cleanedBacktrace = $backtrace;
        $index = 0;

        while ($index < \count($backtrace)) {
            if (isset($backtrace[$index]['file'], $backtrace[$index]['line']) && $backtrace[$index]['line'] === $line && $backtrace[$index]['file'] === $file) {
                $cleanedBacktrace = \array_slice($cleanedBacktrace, 1 + $index);

                break;
            }

            ++$index;
        }

        return $cleanedBacktrace;
    }

    /**
     * Invokes all the listeners and pass the exception to all of them.
     *
     * @param callable[] $listeners The array of listeners to be called
     * @param \Throwable $throwable The exception to be passed onto listeners
     */
    private function invokeListeners(array $listeners, \Throwable $throwable): void
    {
        foreach ($listeners as $listener) {
            try {
                $listener($throwable);
            } catch (\Throwable $exception) {
                // Do nothing as this should be as transparent as possible
            }
        }
    }
}

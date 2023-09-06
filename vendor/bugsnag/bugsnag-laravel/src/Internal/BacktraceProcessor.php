<?php

namespace Bugsnag\BugsnagLaravel\Internal;

/**
 * The criteria for an error to be unhandled in a Laravel or Lumen app is as
 * follows.
 *
 * 1. All unhandled exceptions must pass through one of the `HANDLER_CLASSES`
 *    report method
 * 2. Unhandled exceptions will have had a caller from inside a vendor namespace
 *    or the App exception handler
 * 3. The above exception handler must have originally been called from
 *    within a vendor namespace
 */
final class BacktraceProcessor
{
    /**
     * Searching for a frame where the framework's error handler is called.
     */
    const STATE_FRAMEWORK_HANDLER = 1;

    /**
     * Searching for a frame where the framework's error handler was called by
     * the framework itself, or the user's exception handler.
     */
    const STATE_HANDLER_CALLER = 2;

    /**
     * Deciding if the frame was unhandled.
     */
    const STATE_IS_UNHANDLED = 3;

    /**
     * A state to signal that we're done processing frames and know if the error
     * was unhandled.
     */
    const STATE_DONE = 4;

    /**
     * Laravel and Lumen use different exception handlers which live in different
     * namespaces.
     */
    const LARAVEL_HANDLER_CLASS = \Illuminate\Foundation\Exceptions\Handler::class;
    const LUMEN_HANDLER_CLASS = \Laravel\Lumen\Exceptions\Handler::class;

    /**
     * The method used by the x_HANDLER_CLASS to report errors.
     */
    const HANDLER_METHOD = 'report';

    /**
     * Laravel uses the "Exception" namespace, Lumen uses the plural "Exceptions"
     * for the default app exception handler.
     */
    const LARAVEL_APP_EXCEPTION_HANDLER = \App\Exception\Handler::class;
    const LUMEN_APP_EXCEPTION_HANDLER = \App\Exceptions\Handler::class;

    /**
     * Namespaces used by Laravel and Lumen so we can determine if code was
     * called by the user's app or the framework itself.
     *
     * Note this is not a mistake - Lumen uses the 'Laravel' namespace but
     * Laravel itself does not
     */
    const LARAVEL_VENDOR_NAMESPACE = 'Illuminate\\';
    const LUMEN_VENDOR_NAMESPACE = 'Laravel\\';
    const COLLISION_VENDOR_NAMESPACE = 'NunoMaduro\\Collision\\';

    /**
     * The current state; one of the self::STATE_ constants.
     *
     * @var int
     */
    private $state = self::STATE_FRAMEWORK_HANDLER;

    /**
     * This flag will be set to 'true' if we determine the error was unhandled.
     *
     * @var bool
     */
    private $unhandled = false;

    /**
     * A backtrace matching the format of PHP's 'debug_backtrace'.
     *
     * @var array
     */
    private $backtrace;

    /**
     * @param array $backtrace
     */
    public function __construct(array $backtrace)
    {
        $this->backtrace = $backtrace;
    }

    /**
     * Determine if the backtrace was from an unhandled error.
     *
     * @return bool
     */
    public function isUnhandled()
    {
        foreach ($this->backtrace as $frame) {
            $this->processFrame($frame);

            // stop iterating early if we know we're done
            if ($this->state === self::STATE_DONE) {
                break;
            }
        }

        return $this->unhandled;
    }

    /**
     * @param array $frame
     *
     * @return void
     */
    private function processFrame(array $frame)
    {
        if (!isset($frame['class'])) {
            return;
        }

        $class = $frame['class'];

        switch ($this->state) {
            case self::STATE_FRAMEWORK_HANDLER:
                // if this class is a framework exception handler and the function
                // matches self::HANDLER_METHOD, we can move on to searching for
                // the caller
                if ($this->isFrameworkExceptionHandler($class)
                    && isset($frame['function'])
                    && $frame['function'] === self::HANDLER_METHOD
                ) {
                    $this->state = self::STATE_HANDLER_CALLER;
                }

                break;

            case self::STATE_HANDLER_CALLER:
                // if this is an app exception handler or a framework class, we
                // can move on to determine if this was unhandled or not
                if ($this->isAppExceptionHandler($class) || $this->isVendor($class)) {
                    $this->state = self::STATE_IS_UNHANDLED;
                }

                break;

            case self::STATE_IS_UNHANDLED:
                // we are only interested in running this once so move immediately
                // into the "done" state. This ensures we only check the frame
                // immediately before the caller of the exception handler
                $this->state = self::STATE_DONE;

                // if this class is internal to the framework then the exception
                // was unhandled
                if ($this->isVendor($class)) {
                    $this->unhandled = true;
                }

                break;
        }
    }

    /**
     * Does the given class belong to a vendor namespace?
     *
     * @see self::VENDOR_NAMESPACES
     *
     * @param string $class
     *
     * @return bool
     */
    private function isVendor($class)
    {
        return $this->isInNamespace($class, self::LARAVEL_VENDOR_NAMESPACE)
            || $this->isInNamespace($class, self::LUMEN_VENDOR_NAMESPACE)
            || $this->isInNamespace($class, self::COLLISION_VENDOR_NAMESPACE);
    }

    /**
     * Check if the given class is in the given namespace.
     *
     * @param string $class
     * @param string $namespace
     *
     * @return bool
     */
    private function isInNamespace($class, $namespace)
    {
        return substr($class, 0, strlen($namespace)) === $namespace;
    }

    /**
     * Is the given class Laravel or Lumen's exception handler?
     *
     * @param string $class
     *
     * @return bool
     */
    private function isFrameworkExceptionHandler($class)
    {
        return $class === self::LARAVEL_HANDLER_CLASS
            || $class === self::LUMEN_HANDLER_CLASS;
    }

    /**
     * Is the given class an App's exception handler?
     *
     * @param string $class
     *
     * @return bool
     */
    private function isAppExceptionHandler($class)
    {
        return $class === self::LARAVEL_APP_EXCEPTION_HANDLER
            || $class === self::LUMEN_APP_EXCEPTION_HANDLER;
    }
}

<?php

namespace Dingo\Api\Exception;

use Dingo\Api\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Dingo\Api\Contract\Debug\ExceptionHandler;
use Dingo\Api\Contract\Debug\MessageBagErrors;
use Illuminate\Contracts\Debug\ExceptionHandler as IlluminateExceptionHandler;
use Illuminate\Validation\ValidationException;
use ReflectionFunction;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler implements ExceptionHandler, IlluminateExceptionHandler
{
    /**
     * Array of exception handlers.
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Generic response format.
     *
     * @var array
     */
    protected $format;

    /**
     * Indicates if we are in debug mode.
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * User defined replacements to merge with defaults.
     *
     * @var array
     */
    protected $replacements = [];

    /**
     * The parent Illuminate exception handler instance.
     *
     * @var IlluminateExceptionHandler
     */
    protected $parentHandler;

    /**
     * Create a new exception handler instance.
     *
     * @param IlluminateExceptionHandler $parentHandler
     * @param array                                        $format
     * @param bool                                         $debug
     *
     * @return void
     */
    public function __construct(IlluminateExceptionHandler $parentHandler, array $format, $debug)
    {
        $this->parentHandler = $parentHandler;
        $this->format = $format;
        $this->debug = $debug;
    }

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     *
     * @return void
     */
    public function report(Throwable $throwable)
    {
        $this->parentHandler->report($throwable);
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param Throwable $e
     *
     * @return bool
     */
    public function shouldReport(Throwable $e)
    {
        return true;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function render($request, Throwable $exception)
    {
        return $this->handle($exception);
    }

    /**
     * Render an exception to the console.
     *
     * @param OutputInterface $output
     * @param Throwable $exception
     *
     * @return mixed
     */
    public function renderForConsole($output, Throwable $exception)
    {
        return $this->parentHandler->renderForConsole($output, $exception);
    }

    /**
     * Register a new exception handler.
     *
     * @param callable $callback
     *
     * @return void
     */
    public function register(callable $callback)
    {
        $hint = $this->handlerHint($callback);

        $this->handlers[$hint] = $callback;
    }

    /**
     * Handle an exception if it has an existing handler.
     *
     * @param Throwable|Exception $exception
     *
     * @return Response
     */
    public function handle($exception)
    {
        // Convert Eloquent's 500 ModelNotFoundException into a 404 NotFoundHttpException
        if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        }

        foreach ($this->handlers as $hint => $handler) {
            if (! $exception instanceof $hint) {
                continue;
            }

            if ($response = $handler($exception)) {
                if (! $response instanceof BaseResponse) {
                    $response = new Response($response, $this->getExceptionStatusCode($exception));
                }

                return $response->withException($exception);
            }
        }

        return $this->genericResponse($exception)->withException($exception);
    }

    /**
     * Handle a generic error response if there is no handler available.
     *
     * @param Throwable $exception
     *
     * @throws Throwable
     *
     * @return Response
     */
    protected function genericResponse(Throwable $exception)
    {
        $replacements = $this->prepareReplacements($exception);

        $response = $this->newResponseArray();

        array_walk_recursive($response, function (&$value, $key) use ($replacements) {
            if (Str::startsWith($value, ':') && isset($replacements[$value])) {
                $value = $replacements[$value];
            }
        });

        $response = $this->recursivelyRemoveEmptyReplacements($response);

        return new Response($response, $this->getStatusCode($exception), $this->getHeaders($exception));
    }

    /**
     * Get the status code from the exception.
     *
     * @param Throwable $exception
     *
     * @return int
     */
    protected function getStatusCode(Throwable $exception)
    {
        $statusCode = null;

        if ($exception instanceof ValidationException) {
            $statusCode = $exception->status;
        } elseif ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } else {
            // By default throw 500
            $statusCode = 500;
        }

        // Be extra defensive
        if ($statusCode < 100 || $statusCode > 599) {
            $statusCode = 500;
        }

        return $statusCode;
    }

    /**
     * Get the headers from the exception.
     *
     * @param Throwable $exception
     *
     * @return array
     */
    protected function getHeaders(Throwable $exception)
    {
        return $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];
    }

    /**
     * Prepare the replacements array by gathering the keys and values.
     *
     * @param Throwable $exception
     *
     * @return array
     */
    protected function prepareReplacements(Throwable $exception)
    {
        $statusCode = $this->getStatusCode($exception);

        if (! $message = $exception->getMessage()) {
            $message = sprintf('%d %s', $statusCode, Response::$statusTexts[$statusCode]);
        }

        $replacements = [
            ':message' => $message,
            ':status_code' => $statusCode,
        ];

        if ($exception instanceof MessageBagErrors && $exception->hasErrors()) {
            $replacements[':errors'] = $exception->getErrors();
        }

        if ($exception instanceof ValidationException) {
            $replacements[':errors'] = $exception->errors();
            $replacements[':status_code'] = $exception->status;
        }

        if ($code = $exception->getCode()) {
            $replacements[':code'] = $code;
        }

        if ($this->runningInDebugMode()) {
            $replacements[':debug'] = [
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'class' => get_class($exception),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];

            // Attach trace of previous exception, if exists
            if (! is_null($exception->getPrevious())) {
                $currentTrace = $replacements[':debug']['trace'];

                $replacements[':debug']['trace'] = [
                    'previous' => explode("\n", $exception->getPrevious()->getTraceAsString()),
                    'current' => $currentTrace,
                ];
            }
        }

        return array_merge($replacements, $this->replacements);
    }

    /**
     * Set user defined replacements.
     *
     * @param array $replacements
     *
     * @return void
     */
    public function setReplacements(array $replacements)
    {
        $this->replacements = $replacements;
    }

    /**
     * Recursively remove any empty replacement values in the response array.
     *
     * @param array $input
     *
     * @return array
     */
    protected function recursivelyRemoveEmptyReplacements(array $input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->recursivelyRemoveEmptyReplacements($value);
            }
        }

        return array_filter($input, function ($value) {
            if (is_string($value)) {
                return ! Str::startsWith($value, ':');
            }

            return true;
        });
    }

    /**
     * Create a new response array with replacement values.
     *
     * @return array
     */
    protected function newResponseArray()
    {
        return $this->format;
    }

    /**
     * Get the exception status code.
     *
     * @param Exception $exception
     * @param int        $defaultStatusCode
     *
     * @return int
     */
    protected function getExceptionStatusCode(Exception $exception, $defaultStatusCode = 500)
    {
        return ($exception instanceof HttpExceptionInterface) ? $exception->getStatusCode() : $defaultStatusCode;
    }

    /**
     * Determines if we are running in debug mode.
     *
     * @return bool
     */
    protected function runningInDebugMode()
    {
        return $this->debug;
    }

    /**
     * Get the hint for an exception handler.
     *
     * @param callable $callback
     *
     * @return string
     */
    protected function handlerHint(callable $callback)
    {
        $reflection = new ReflectionFunction($callback);

        $exception = $reflection->getParameters()[0];

        return $exception->getClass()->getName();
    }

    /**
     * Get the exception handlers.
     *
     * @return array
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Set the error format array.
     *
     * @param array $format
     *
     * @return void
     */
    public function setErrorFormat(array $format)
    {
        $this->format = $format;
    }

    /**
     * Set the debug mode.
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }
}

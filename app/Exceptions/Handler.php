<?php

namespace App\Exceptions;

use App\Exceptions\Http\Resource as ResourceException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->isApi()) {
            return $this->handleApiExceptions($request, $exception);
        }

        if (config('app.debug') === false) {
            return $this->handleWebExceptions($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Store the current url in the session
        if ($request->url() !== config('app.url')) {
            session(['url.intended' => $request->url()]);
        }

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->to($exception->redirectTo() ?? route('login'));
    }

    protected function handleWebExceptions($request, $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            // ajax 404 json feedback
            if ($request->ajax()) {
                return response()->json(['error' => 'Not Found'], 404);
            }

            flash(trans('errors.body.page_not_found'))->error()->important();

            // normal 404 view page feedback
            return redirect()
                ->back()
                ->withErrors(['msg', trans('errors.body.page_not_found')]);
        }

        if ($exception instanceof ModelNotFoundException) {
            // ajax 404 json feedback
            if ($request->ajax()) {
                return response()->json(['error' => 'Not Found'], 404);
            }

            try {
                $names = explode('.', $request->route()->getName());
                $names[count($names) - 1] = 'index';

                $route = route(implode('.', $names));

                flash(trans('errors.message.record'))->warning()->important();

                return redirect($route);
            } catch (\Exception $e) {
                // normal 404 view page feedback
                return response()->view('errors.404', [], 404);
            }
        }

        if ($exception instanceof FatalThrowableError) {
            // ajax 500 json feedback
            if ($request->ajax()) {
                return response()->json(['error' => 'Error Page'], 500);
            }

            // normal 500 view page feedback
            return response()->view('errors.500', [], 500);
        }

        if ($exception instanceof ThrottleRequestsException) {
            // ajax 500 json feedback
            if ($request->ajax()) {
                return response()->json(['error' => $exception->getMessage()], 429);
            }
        }

        return parent::render($request, $exception);
    }

    protected function handleApiExceptions($request, $exception): Response
    {
        $replacements = $this->prepareApiReplacements($exception);

        $response = config('api.error_format');

        array_walk_recursive($response, function (&$value, $key) use ($replacements) {
            if (Str::startsWith($value, ':') && isset($replacements[$value])) {
                $value = $replacements[$value];
            }
        });

        $response = $this->recursivelyRemoveEmptyApiReplacements($response);

        return new Response($response, $this->getStatusCode($exception), $this->getHeaders($exception));
    }

    /**
     * Prepare the replacements array by gathering the keys and values.
     *
     * @param Throwable $exception
     *
     * @return array
     */
    protected function prepareApiReplacements(Throwable $exception): array
    {
        $code = $this->getStatusCode($exception);

        if (! $message = $exception->getMessage()) {
            $message = sprintf('%d %s', $code, Response::$statusTexts[$code]);
        }

        $replacements = [
            ':message' => $message,
            ':status_code' => $code,
        ];

        if ($exception instanceof ResourceException && $exception->hasErrors()) {
            $replacements[':errors'] = $exception->getErrors();
        }

        if ($exception instanceof ValidationException) {
            $replacements[':errors'] = $exception->errors();
            $replacements[':status_code'] = $exception->status;
        }

        if ($code = $exception->getCode()) {
            $replacements[':code'] = $code;
        }

        if (config('api.debug')) {
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

        return $replacements;
    }

    /**
     * Recursively remove any empty replacement values in the response array.
     *
     * @param array $input
     *
     * @return array
     */
    protected function recursivelyRemoveEmptyApiReplacements(array $input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->recursivelyRemoveEmptyApiReplacements($value);
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
     * Get the status code from the exception.
     *
     * @param Throwable $exception
     *
     * @return int
     */
    protected function getStatusCode(Throwable $exception): int
    {
        $code = null;

        if ($exception instanceof ValidationException) {
            $code = $exception->status;
        } elseif ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        } else {
            // By default throw 500
            $code = 500;
        }

        // Be extra defensive
        if ($code < 100 || $code > 599) {
            $code = 500;
        }

        return $code;
    }

    /**
     * Get the headers from the exception.
     *
     * @param Throwable $exception
     *
     * @return array
     */
    protected function getHeaders(Throwable $exception): array
    {
        return ($exception instanceof HttpExceptionInterface)
                ? $exception->getHeaders()
                : [];
    }
}

<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FatalThrowableError;
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
        if (config('app.debug') === false) {
            return $this->handleExceptions($request, $exception);
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

    private function handleExceptions($request, $exception)
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

        return parent::render($request, $exception);
    }
}

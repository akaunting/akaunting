<?php

namespace App\Abstracts\Http;

use App\Traits\Jobs;
use App\Traits\Permissions;
use App\Traits\Relationships;
use App\Exceptions\Http\Resource as ResourceException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ApiController extends BaseController
{
    use AuthorizesRequests, Jobs, Permissions, Relationships, ValidatesRequests;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->assignPermissionsToController();
    }

    /**
     * Create the response for when a request fails validation.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if ($request->expectsJson()) {
            throw new ResourceException('Validation Error', $errors);
        }

        return redirect()->to($this->getRedirectUrl())->withInput($request->input())->withErrors($errors, $this->errorBag());
    }

    /**
     * Respond with a location and a created resource.
     *
     * @param string $location
     * @param object $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($location, $resource): JsonResponse
    {
        return $resource
                ->response()
                ->setStatusCode(201)
                ->header('Location', $location);
    }

    /**
     * Respond with a location and an accepted resource.
     *
     * @param string $location
     * @param object $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accepted($location, $resource): JsonResponse
    {
        return $resource
                ->response()
                ->setStatusCode(202)
                ->header('Location', $location);
    }

    /**
     * Respond with empty content.
     *
     * @return \Illuminate\Http\Response
     */
    public function noContent(): Response
    {
        return (new Response)
                ->setStatusCode(204);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int    $statusCode
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function error($message, $statusCode)
    {
        throw new HttpException($statusCode, $message);
    }

    /**
     * Return a 404 not found error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorNotFound($message = 'Not Found')
    {
        $this->error($message, 404);
    }

    /**
     * Return a 400 bad request error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorBadRequest($message = 'Bad Request')
    {
        $this->error($message, 400);
    }

    /**
     * Return a 403 forbidden error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorForbidden($message = 'Forbidden')
    {
        $this->error($message, 403);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorInternal($message = 'Internal Error')
    {
        $this->error($message, 500);
    }

    /**
     * Return a 401 unauthorized error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        $this->error($message, 401);
    }

    /**
     * Return a 405 method not allowed error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        $this->error($message, 405);
    }

    /**
     * The type the permission of the contacts and documents endpoints is checked
     * for, taken from the search like ?search=type:invoice.
     *
     * @see \App\Traits\Permissions::assignPermissionsToController()
     */
    public function getSearchType(): string
    {
        $type = $this->getSearchStringValue('type');

        return is_string($type) ? $type : '';
    }

    /**
     * The permission is checked for the searched type, so a record of another
     * type must not be reached, a bill with the permission of invoices for instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function authorizeType($model): void
    {
        if (empty($model) || ($model->type !== $this->getSearchType())) {
            $this->errorNotFound();
        }
    }

    /**
     * A record must not be created as or turned into another type than the searched one.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function authorizeRequestType($request): void
    {
        if ($request->has('type') && ($request->get('type') !== $this->getSearchType())) {
            $this->errorForbidden();
        }
    }
}

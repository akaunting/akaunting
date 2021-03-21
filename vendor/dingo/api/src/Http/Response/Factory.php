<?php

namespace Dingo\Api\Http\Response;

use Closure;
use ErrorException;
use Illuminate\Support\Str;
use Dingo\Api\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Dingo\Api\Transformer\Factory as TransformerFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Factory
{
    /**
     * Transformer factory instance.
     *
     * @var \Dingo\Api\Transformer\Factory
     */
    protected $transformer;

    /**
     * Create a new response factory instance.
     *
     * @param \Dingo\Api\Transformer\Factory $transformer
     *
     * @return void
     */
    public function __construct(TransformerFactory $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Respond with a created response and associate a location if provided.
     *
     * @param null|string $location
     *
     * @return \Dingo\Api\Http\Response
     */
    public function created($location = null, $content = null)
    {
        $response = new Response($content);
        $response->setStatusCode(201);

        if (! is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with an accepted response and associate a location and/or content if provided.
     *
     * @param null|string $location
     * @param mixed       $content
     *
     * @return \Dingo\Api\Http\Response
     */
    public function accepted($location = null, $content = null)
    {
        $response = new Response($content);
        $response->setStatusCode(202);

        if (! is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a no content response.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function noContent()
    {
        $response = new Response(null);

        return $response->setStatusCode(204);
    }

    /**
     * Bind a collection to a transformer and start building a response.
     *
     * @param \Illuminate\Support\Collection $collection
     * @param string|callable|object         $transformer
     * @param array|\Closure                 $parameters
     * @param \Closure|null                  $after
     *
     * @return \Dingo\Api\Http\Response
     */
    public function collection(Collection $collection, $transformer = null, $parameters = [], Closure $after = null)
    {
        if ($collection->isEmpty()) {
            $class = get_class($collection);
        } else {
            $class = get_class($collection->first());
        }

        if ($parameters instanceof \Closure) {
            $after = $parameters;
            $parameters = [];
        }

        if ($transformer !== null) {
            $binding = $this->transformer->register($class, $transformer, $parameters, $after);
        } else {
            $binding = $this->transformer->getBinding($collection);
        }

        return new Response($collection, 200, [], $binding);
    }

    /**
     * Bind an item to a transformer and start building a response.
     *
     * @param object                         $item
     * @param null|string|callable|object    $transformer
     * @param array                          $parameters
     * @param \Closure                       $after
     *
     * @return \Dingo\Api\Http\Response
     */
    public function item($item, $transformer = null, $parameters = [], Closure $after = null)
    {
        // Check for $item being null
        if (! is_null($item)) {
            $class = get_class($item);
        } else {
            $class = \StdClass::class;
        }

        if ($parameters instanceof \Closure) {
            $after = $parameters;
            $parameters = [];
        }

        if ($transformer !== null) {
            $binding = $this->transformer->register($class, $transformer, $parameters, $after);
        } else {
            $binding = $this->transformer->getBinding($item);
        }

        return new Response($item, 200, [], $binding);
    }

    /**
     * Bind an arbitrary array to a transformer and start building a response.
     *
     * @param array $array
     * @param $transformer
     * @param array $parameters
     * @param Closure|null $after
     *
     * @return Response
     */
    public function array(array $array, $transformer = null, $parameters = [], Closure $after = null)
    {
        if ($parameters instanceof \Closure) {
            $after = $parameters;
            $parameters = [];
        }

        // For backwards compatibility, allow no transformer
        if ($transformer) {
            // Use the PHP stdClass for this purpose, as a work-around, since we need to register a class binding
            $class = 'stdClass';
            // This will convert the array into an stdClass
            $array = (object) $array;

            $binding = $this->transformer->register($class, $transformer, $parameters, $after);
        } else {
            $binding = null;
        }

        return new Response($array, 200, [], $binding);
    }

    /**
     * Bind a paginator to a transformer and start building a response.
     *
     * @param \Illuminate\Contracts\Pagination\Paginator $paginator
     * @param null|string|callable|object                $transformer
     * @param array                                      $parameters
     * @param \Closure                                   $after
     *
     * @return \Dingo\Api\Http\Response
     */
    public function paginator(Paginator $paginator, $transformer = null, array $parameters = [], Closure $after = null)
    {
        if ($paginator->isEmpty()) {
            $class = get_class($paginator);
        } else {
            $class = get_class($paginator->first());
        }

        if ($transformer !== null) {
            $binding = $this->transformer->register($class, $transformer, $parameters, $after);
        } else {
            $binding = $this->transformer->getBinding($paginator->first());
        }

        return new Response($paginator, 200, [], $binding);
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
     * Call magic methods beginning with "with".
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'with')) {
            return call_user_func_array([$this, Str::camel(substr($method, 4))], $parameters);

        // Because PHP won't let us name the method "array" we'll simply watch for it
            // in here and return the new binding. Gross. This is now DEPRECATED and
            // should not be used. Just return an array or a new response instance.
        } elseif ($method == 'array') {
            return new Response($parameters[0]);
        }

        throw new ErrorException('Undefined method '.get_class($this).'::'.$method);
    }
}

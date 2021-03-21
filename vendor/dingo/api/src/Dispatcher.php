<?php

namespace Dingo\Api;

use Dingo\Api\Auth\Auth;
use Illuminate\Support\Str;
use Dingo\Api\Routing\Router;
use Dingo\Api\Http\InternalRequest;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Cookie;
use Dingo\Api\Exception\InternalHttpException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Request as RequestFacade;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Dispatcher
{
    /**
     * Illuminate container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Illuminate filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Router instance.
     *
     * @var \Dingo\Api\Routing\Router
     */
    protected $router;

    /**
     * Auth instance.
     *
     * @var \Dingo\Api\Auth\Auth
     */
    protected $auth;

    /**
     * Internal request stack.
     *
     * @var array
     */
    protected $requestStack = [];

    /**
     * Internal route stack.
     *
     * @var array
     */
    protected $routeStack = [];

    /**
     * Version for the request.
     *
     * @var string
     */
    protected $version;

    /**
     * Request headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Request cookies.
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * Request parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Request raw content.
     *
     * @var string
     */
    protected $content;

    /**
     * Request uploaded files.
     *
     * @var array
     */
    protected $uploads = [];

    /**
     * Domain for the request.
     *
     * @var string
     */
    protected $domain;

    /**
     * Indicates whether the returned response is the raw response object.
     *
     * @var bool
     */
    protected $raw = false;

    /**
     * Indicates whether authentication is persisted.
     *
     * @var bool
     */
    protected $persistAuthentication = true;

    /**
     * API subtype.
     *
     * @var string
     */
    protected $subtype;

    /**
     * API standards tree.
     *
     * @var string
     */
    protected $standardsTree;

    /**
     * API prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Default version.
     *
     * @var string
     */
    protected $defaultVersion;

    /**
     * Default domain.
     *
     * @var string
     */
    protected $defaultDomain;

    /**
     * Default format.
     *
     * @var string
     */
    protected $defaultFormat;

    /**
     * Create a new dispatcher instance.
     *
     * @param \Illuminate\Container\Container   $container
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Dingo\Api\Routing\Router         $router
     * @param \Dingo\Api\Auth\Auth              $auth
     *
     * @return void
     */
    public function __construct(Container $container, Filesystem $files, Router $router, Auth $auth)
    {
        $this->container = $container;
        $this->files = $files;
        $this->router = $router;
        $this->auth = $auth;

        $this->setupRequestStack();
    }

    /**
     * Setup the request stack by grabbing the initial request.
     *
     * @return void
     */
    protected function setupRequestStack()
    {
        $this->requestStack[] = $this->container['request'];
    }

    /**
     * Attach files to be uploaded.
     *
     * @param array $files
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function attach(array $files)
    {
        foreach ($files as $key => $file) {
            if (is_array($file)) {
                $file = new UploadedFile($file['path'], basename($file['path']), $file['mime'], $file['size']);
            } elseif (is_string($file)) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                $file = new UploadedFile($file, basename($file), finfo_file($finfo, $file), $this->files->size($file));
            } elseif (! $file instanceof UploadedFile) {
                continue;
            }

            $this->uploads[$key] = $file;
        }

        return $this;
    }

    /**
     * Internal request will be authenticated as the given user.
     *
     * @param mixed $user
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function be($user)
    {
        $this->auth->setUser($user);

        return $this;
    }

    /**
     * Send a JSON payload in the request body.
     *
     * @param string|array $content
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function json($content)
    {
        if (is_array($content)) {
            $content = json_encode($content);
        }

        $this->content = $content;

        return $this->header('Content-Type', 'application/json');
    }

    /**
     * Sets the domain to be used for the request.
     *
     * @param string $domain
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function on($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Return the raw response object once request is dispatched.
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function raw()
    {
        $this->raw = true;

        return $this;
    }

    /**
     * Only authenticate with the given user for a single request.
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function once()
    {
        $this->persistAuthentication = false;

        return $this;
    }

    /**
     * Set the version of the API for the next request.
     *
     * @param string $version
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function version($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Set the parameters to be sent on the next API request.
     *
     * @param string|array $parameters
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function with($parameters)
    {
        $this->parameters = array_merge($this->parameters, is_array($parameters) ? $parameters : func_get_args());

        return $this;
    }

    /**
     * Set a header to be sent on the next API request.
     *
     * @param string $key
     * @param string $value
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function header($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set a cookie to be sent on the next API request.
     *
     * @param \Symfony\Component\HttpFoundation\Cookie $cookie
     *
     * @return \Dingo\Api\Dispatcher
     */
    public function cookie(Cookie $cookie)
    {
        $this->cookies[] = $cookie;

        return $this;
    }

    /**
     * Perform API GET request.
     *
     * @param string       $uri
     * @param string|array $parameters
     *
     * @return mixed
     */
    public function get($uri, $parameters = [])
    {
        return $this->queueRequest('get', $uri, $parameters);
    }

    /**
     * Perform API POST request.
     *
     * @param string       $uri
     * @param string|array $parameters
     * @param string       $content
     *
     * @return mixed
     */
    public function post($uri, $parameters = [], $content = '')
    {
        return $this->queueRequest('post', $uri, $parameters, $content);
    }

    /**
     * Perform API PUT request.
     *
     * @param string       $uri
     * @param string|array $parameters
     * @param string       $content
     *
     * @return mixed
     */
    public function put($uri, $parameters = [], $content = '')
    {
        return $this->queueRequest('put', $uri, $parameters, $content);
    }

    /**
     * Perform API PATCH request.
     *
     * @param string       $uri
     * @param string|array $parameters
     * @param string       $content
     *
     * @return mixed
     */
    public function patch($uri, $parameters = [], $content = '')
    {
        return $this->queueRequest('patch', $uri, $parameters, $content);
    }

    /**
     * Perform API DELETE request.
     *
     * @param string       $uri
     * @param string|array $parameters
     * @param string       $content
     *
     * @return mixed
     */
    public function delete($uri, $parameters = [], $content = '')
    {
        return $this->queueRequest('delete', $uri, $parameters, $content);
    }

    /**
     * Queue up and dispatch a new request.
     *
     * @param string       $verb
     * @param string       $uri
     * @param string|array $parameters
     * @param string       $content
     *
     * @return mixed
     */
    protected function queueRequest($verb, $uri, $parameters, $content = '')
    {
        if (! empty($content)) {
            $this->content = $content;
        }

        // Sometimes after setting the initial request another request might be made prior to
        // internally dispatching an API request. We need to capture this request as well
        // and add it to the request stack as it has become the new parent request to
        // this internal request. This will generally occur during tests when
        // using the crawler to navigate pages that also make internal
        // requests.
        if (end($this->requestStack) != $this->container['request']) {
            $this->requestStack[] = $this->container['request'];
        }

        $this->requestStack[] = $request = $this->createRequest($verb, $uri, $parameters);

        return $this->dispatch($request);
    }

    /**
     * Create a new internal request from an HTTP verb and URI.
     *
     * @param string       $verb
     * @param string       $uri
     * @param string|array $parameters
     *
     * @return \Dingo\Api\Http\InternalRequest
     */
    protected function createRequest($verb, $uri, $parameters)
    {
        $parameters = array_merge($this->parameters, (array) $parameters);

        $uri = $this->addPrefixToUri($uri);

        // If the URI does not have a scheme then we can assume that there it is not an
        // absolute URI, in this case we'll prefix the root requests path to the URI.
        $rootUrl = $this->getRootRequest()->root();
        if ((! parse_url($uri, PHP_URL_SCHEME)) && parse_url($rootUrl) !== false) {
            $uri = rtrim($rootUrl, '/').'/'.ltrim($uri, '/');
        }

        $request = InternalRequest::create(
            $uri,
            $verb,
            $parameters,
            $this->cookies,
            $this->uploads,
            $this->container['request']->server->all(),
            $this->content
        );

        $request->headers->set('host', $this->getDomain());

        foreach ($this->headers as $header => $value) {
            $request->headers->set($header, $value);
        }

        $request->headers->set('accept', $this->getAcceptHeader());

        return $request;
    }

    /**
     * Add the prefix to the URI.
     *
     * @param string $uri
     *
     * @return string
     */
    protected function addPrefixToUri($uri)
    {
        if (! isset($this->prefix)) {
            return $uri;
        }

        $uri = trim($uri, '/');

        if (Str::startsWith($uri, $this->prefix)) {
            return $uri;
        }

        return rtrim('/'.trim($this->prefix, '/').'/'.$uri, '/');
    }

    /**
     * Build the "Accept" header.
     *
     * @return string
     */
    protected function getAcceptHeader()
    {
        return sprintf('application/%s.%s.%s+%s', $this->getStandardsTree(), $this->getSubtype(), $this->getVersion(), $this->getFormat());
    }

    /**
     * Attempt to dispatch an internal request.
     *
     * @param \Dingo\Api\Http\InternalRequest $request
     *
     * @throws \Exception|\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
     *
     * @return mixed
     */
    protected function dispatch(InternalRequest $request)
    {
        $this->routeStack[] = $this->router->getCurrentRoute();

        $this->clearCachedFacadeInstance();

        try {
            $this->container->instance('request', $request);

            $response = $this->router->dispatch($request);

            if (! $response->isSuccessful() && ! $response->isRedirection()) {
                throw new InternalHttpException($response);
            }

            if (! $this->raw) {
                $response = $response->getOriginalContent();
            }
        } catch (HttpExceptionInterface $exception) {
            $this->refreshRequestStack();

            throw $exception;
        }

        $this->refreshRequestStack();

        return $response;
    }

    /**
     * Refresh the request stack.
     *
     * This is done by resetting the authentication, popping
     * the last request from the stack, replacing the input,
     * and resetting the version and parameters.
     *
     * @return void
     */
    protected function refreshRequestStack()
    {
        if (! $this->persistAuthentication) {
            $this->auth->setUser(null);

            $this->persistAuthentication = true;
        }

        if ($route = array_pop($this->routeStack)) {
            $this->router->setCurrentRoute($route);
        }

        $this->replaceRequestInstance();

        $this->clearCachedFacadeInstance();

        $this->raw = false;

        $this->version = $this->domain = $this->content = null;

        $this->parameters = $this->uploads = [];
    }

    /**
     * Replace the request instance with the previous request instance.
     *
     * @return void
     */
    protected function replaceRequestInstance()
    {
        array_pop($this->requestStack);

        $this->container->instance('request', end($this->requestStack));
    }

    /**
     * Clear the cached facade instance.
     *
     * @return void
     */
    protected function clearCachedFacadeInstance()
    {
        // Facades cache the resolved instance so we need to clear out the
        // request instance that may have been cached. Otherwise we'll
        // may get unexpected results.
        RequestFacade::clearResolvedInstance('request');
    }

    /**
     * Get the root request instance.
     *
     * @return \Illuminate\Http\Request
     */
    protected function getRootRequest()
    {
        return reset($this->requestStack);
    }

    /**
     * Get the domain.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain ?: $this->defaultDomain;
    }

    /**
     * Get the version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version ?: $this->defaultVersion;
    }

    /**
     * Get the format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->defaultFormat;
    }

    /**
     * Get the subtype.
     *
     * @return string
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * Set the subtype.
     *
     * @param string $subtype
     *
     * @return void
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
    }

    /**
     * Get the standards tree.
     *
     * @return string
     */
    public function getStandardsTree()
    {
        return $this->standardsTree;
    }

    /**
     * Set the standards tree.
     *
     * @param string $standardsTree
     *
     * @return void
     */
    public function setStandardsTree($standardsTree)
    {
        $this->standardsTree = $standardsTree;
    }

    /**
     * Set the prefix.
     *
     * @param string $prefix
     *
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Set the default version.
     *
     * @param string $version
     *
     * @return void
     */
    public function setDefaultVersion($version)
    {
        $this->defaultVersion = $version;
    }

    /**
     * Set the default domain.
     *
     * @param string $domain
     *
     * @return void
     */
    public function setDefaultDomain($domain)
    {
        $this->defaultDomain = $domain;
    }

    /**
     * Set the default format.
     *
     * @param string $format
     *
     * @return void
     */
    public function setDefaultFormat($format)
    {
        $this->defaultFormat = $format;
    }
}

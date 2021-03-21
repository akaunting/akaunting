<?php

namespace Dingo\Api\Http;

use ArrayObject;
use Illuminate\Support\Str;
use UnexpectedValueException;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Transformer\Binding;
use Dingo\Api\Event\ResponseIsMorphing;
use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Response as IlluminateResponse;
use Dingo\Api\Transformer\Factory as TransformerFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class Response extends IlluminateResponse
{
    /**
     * The exception that triggered the error response.
     *
     * @var \Exception
     */
    public $exception;

    /**
     * Transformer binding instance.
     *
     * @var \Dingo\Api\Transformer\Binding
     */
    protected $binding;

    /**
     * Array of registered formatters.
     *
     * @var array
     */
    protected static $formatters = [];

    /**
     * Array of formats' options.
     *
     * @var array
     */
    protected static $formatsOptions = [];

    /**
     * Transformer factory instance.
     *
     * @var \Dingo\Api\Transformer\TransformerFactory
     */
    protected static $transformer;

    /**
     * Event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $events;

    /**
     * Create a new response instance.
     *
     * @param mixed                          $content
     * @param int                            $status
     * @param array                          $headers
     * @param \Dingo\Api\Transformer\Binding $binding
     *
     * @return void
     */
    public function __construct($content, $status = 200, $headers = [], Binding $binding = null)
    {
        parent::__construct($content, $status, $headers);

        $this->binding = $binding;
    }

    /**
     * Make an API response from an existing Illuminate response.
     *
     * @param \Illuminate\Http\Response $old
     *
     * @return \Dingo\Api\Http\Response
     */
    public static function makeFromExisting(IlluminateResponse $old)
    {
        $new = new static($old->getOriginalContent(), $old->getStatusCode());

        $new->headers = $old->headers;

        return $new;
    }

    /**
     * Make an API response from an existing JSON response.
     *
     * @param \Illuminate\Http\JsonResponse $json
     *
     * @return \Dingo\Api\Http\Response
     */
    public static function makeFromJson(JsonResponse $json)
    {
        $content = $json->getContent();

        // If the contents of the JsonResponse does not starts with /**/ (typical laravel jsonp response)
        // we assume that it is a valid json response that can be decoded, or we just use the raw jsonp
        // contents for building the response
        if (! Str::startsWith($json->getContent(), '/**/')) {
            $content = json_decode($json->getContent(), true);
        }

        $new = new static($content, $json->getStatusCode());

        $new->headers = $json->headers;

        return $new;
    }

    /**
     * Morph the API response to the appropriate format.
     *
     * @param string $format
     *
     * @return \Dingo\Api\Http\Response
     */
    public function morph($format = 'json')
    {
        $this->content = $this->getOriginalContent();

        $this->fireMorphingEvent();

        if (isset(static::$transformer) && static::$transformer->transformableResponse($this->content)) {
            $this->content = static::$transformer->transform($this->content);
        }

        $formatter = static::getFormatter($format);

        $formatter->setOptions(static::getFormatsOptions($format));

        $defaultContentType = $this->headers->get('Content-Type');

        $this->headers->set('Content-Type', $formatter->getContentType());

        $this->fireMorphedEvent();

        if ($this->content instanceof EloquentModel) {
            $this->content = $formatter->formatEloquentModel($this->content);
        } elseif ($this->content instanceof EloquentCollection) {
            $this->content = $formatter->formatEloquentCollection($this->content);
        } elseif (is_array($this->content) || $this->content instanceof ArrayObject || $this->content instanceof Arrayable) {
            $this->content = $formatter->formatArray($this->content);
        } else {
            $this->headers->set('Content-Type', $defaultContentType);
        }

        return $this;
    }

    /**
     * Fire the morphed event.
     *
     * @return void
     */
    protected function fireMorphedEvent()
    {
        if (! static::$events) {
            return;
        }

        static::$events->dispatch(new ResponseWasMorphed($this, $this->content));
    }

    /**
     * Fire the morphing event.
     *
     * @return void
     */
    protected function fireMorphingEvent()
    {
        if (! static::$events) {
            return;
        }

        static::$events->dispatch(new ResponseIsMorphing($this, $this->content));
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        // Attempt to set the content string, if we encounter an unexpected value
        // then we most likely have an object that cannot be type cast. In that
        // case we'll simply leave the content as null and set the original
        // content value and continue.
        if (! empty($content) && is_object($content) && ! $this->shouldBeJson($content)) {
            $this->original = $content;

            return $this;
        }

        try {
            return parent::setContent($content);
        } catch (UnexpectedValueException $exception) {
            $this->original = $content;

            return $this;
        }
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public static function setEventDispatcher(EventDispatcher $events)
    {
        static::$events = $events;
    }

    /**
     * Get the formatter based on the requested format type.
     *
     * @param string $format
     *
     * @throws \RuntimeException
     *
     * @return \Dingo\Api\Http\Response\Format\Format
     */
    public static function getFormatter($format)
    {
        if (! static::hasFormatter($format)) {
            throw new NotAcceptableHttpException('Unable to format response according to Accept header.');
        }

        return static::$formatters[$format];
    }

    /**
     * Determine if a response formatter has been registered.
     *
     * @param string $format
     *
     * @return bool
     */
    public static function hasFormatter($format)
    {
        return isset(static::$formatters[$format]);
    }

    /**
     * Set the response formatters.
     *
     * @param array $formatters
     *
     * @return void
     */
    public static function setFormatters(array $formatters)
    {
        static::$formatters = $formatters;
    }

    /**
     * Set the formats' options.
     *
     * @param array $formatsOptions
     *
     * @return void
     */
    public static function setFormatsOptions(array $formatsOptions)
    {
        static::$formatsOptions = $formatsOptions;
    }

    /**
     * Get the format's options.
     *
     * @param string $format
     *
     * @return array
     */
    public static function getFormatsOptions($format)
    {
        if (! static::hasOptionsForFormat($format)) {
            return [];
        }

        return static::$formatsOptions[$format];
    }

    /**
     * Determine if any format's options were set.
     *
     * @param string $format
     *
     * @return bool
     */
    public static function hasOptionsForFormat($format)
    {
        return isset(static::$formatsOptions[$format]);
    }

    /**
     * Add a response formatter.
     *
     * @param string                                 $key
     * @param \Dingo\Api\Http\Response\Format\Format $formatter
     *
     * @return void
     */
    public static function addFormatter($key, $formatter)
    {
        static::$formatters[$key] = $formatter;
    }

    /**
     * Set the transformer factory instance.
     *
     * @param \Dingo\Api\Transformer\Factory $transformer
     *
     * @return void
     */
    public static function setTransformer(TransformerFactory $transformer)
    {
        static::$transformer = $transformer;
    }

    /**
     * Get the transformer instance.
     *
     * @return \Dingo\Api\Transformer\Factory
     */
    public static function getTransformer()
    {
        return static::$transformer;
    }

    /**
     * Add a meta key and value pair.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return \Dingo\Api\Http\Response
     */
    public function addMeta($key, $value)
    {
        $this->binding->addMeta($key, $value);

        return $this;
    }

    /**
     * Add a meta key and value pair.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return \Dingo\Api\Http\Response
     */
    public function meta($key, $value)
    {
        return $this->addMeta($key, $value);
    }

    /**
     * Set the meta data for the response.
     *
     * @param array $meta
     *
     * @return \Dingo\Api\Http\Response
     */
    public function setMeta(array $meta)
    {
        $this->binding->setMeta($meta);

        return $this;
    }

    /**
     * Get the meta data for the response.
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->binding->getMeta();
    }

    /**
     * Add a cookie to the response.
     *
     * @param \Symfony\Component\HttpFoundation\Cookie|mixed $cookie
     *
     * @return \Dingo\Api\Http\Response
     */
    public function cookie($cookie)
    {
        return $this->withCookie($cookie);
    }

    /**
     * Add a header to the response.
     *
     * @param string $key
     * @param string $value
     * @param bool   $replace
     *
     * @return \Dingo\Api\Http\Response
     */
    public function withHeader($key, $value, $replace = true)
    {
        return $this->header($key, $value, $replace);
    }

    /**
     * Set the response status code.
     *
     * @param int $statusCode
     *
     * @return \Dingo\Api\Http\Response
     */
    public function statusCode($statusCode)
    {
        return $this->setStatusCode($statusCode);
    }
}

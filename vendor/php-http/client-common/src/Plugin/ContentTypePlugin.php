<?php

declare(strict_types=1);

namespace Http\Client\Common\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Allow to set the correct content type header on the request automatically only if it is not set.
 *
 * @author Karim Pinchon <karim.pinchon@gmail.com>
 */
final class ContentTypePlugin implements Plugin
{
    /**
     * Allow to disable the content type detection when stream is too large (as it can consume a lot of resource).
     *
     * @var bool
     *
     * true     skip the content type detection
     * false    detect the content type (default value)
     */
    private $skipDetection;

    /**
     * Determine the size stream limit for which the detection as to be skipped (default to 16Mb).
     *
     * @var int
     */
    private $sizeLimit;

    /**
     * @param array{'skip_detection'?: bool, 'size_limit'?: int} $config
     *
     * Configuration options:
     *   - skip_detection: true skip detection if stream size is bigger than $size_limit
     *   - size_limit: size stream limit for which the detection as to be skipped
     */
    public function __construct(array $config = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'skip_detection' => false,
            'size_limit' => 16000000,
        ]);
        $resolver->setAllowedTypes('skip_detection', 'bool');
        $resolver->setAllowedTypes('size_limit', 'int');

        $options = $resolver->resolve($config);

        $this->skipDetection = $options['skip_detection'];
        $this->sizeLimit = $options['size_limit'];
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if (!$request->hasHeader('Content-Type')) {
            $stream = $request->getBody();
            $streamSize = $stream->getSize();

            if (!$stream->isSeekable()) {
                return $next($request);
            }

            if (0 === $streamSize) {
                return $next($request);
            }

            if ($this->skipDetection && (null === $streamSize || $streamSize >= $this->sizeLimit)) {
                return $next($request);
            }

            if ($this->isJson($stream)) {
                $request = $request->withHeader('Content-Type', 'application/json');

                return $next($request);
            }

            if ($this->isXml($stream)) {
                $request = $request->withHeader('Content-Type', 'application/xml');

                return $next($request);
            }
        }

        return $next($request);
    }

    private function isJson(StreamInterface $stream): bool
    {
        if (!function_exists('json_decode')) {
            return false;
        }
        $stream->rewind();

        json_decode($stream->getContents());

        return JSON_ERROR_NONE === json_last_error();
    }

    private function isXml(StreamInterface $stream): bool
    {
        if (!function_exists('simplexml_load_string')) {
            return false;
        }
        $stream->rewind();

        $previousValue = libxml_use_internal_errors(true);
        $isXml = simplexml_load_string($stream->getContents());
        libxml_use_internal_errors($previousValue);

        return false !== $isXml;
    }
}

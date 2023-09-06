<?php

declare(strict_types=1);

namespace Http\Client\Common\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Add schema, host and port to a request. Can be set to overwrite the schema and host if desired.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AddHostPlugin implements Plugin
{
    /**
     * @var UriInterface
     */
    private $host;

    /**
     * @var bool
     */
    private $replace;

    /**
     * @param array{'replace'?: bool} $config
     *
     * Configuration options:
     *   - replace: True will replace all hosts, false will only add host when none is specified
     */
    public function __construct(UriInterface $host, array $config = [])
    {
        if ('' === $host->getHost()) {
            throw new \LogicException('Host can not be empty');
        }

        $this->host = $host;

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($config);

        $this->replace = $options['replace'];
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if ($this->replace || '' === $request->getUri()->getHost()) {
            $uri = $request->getUri()
                ->withHost($this->host->getHost())
                ->withScheme($this->host->getScheme())
                ->withPort($this->host->getPort())
            ;

            $request = $request->withUri($uri);
        }

        return $next($request);
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'replace' => false,
        ]);
        $resolver->setAllowedTypes('replace', 'bool');
    }
}

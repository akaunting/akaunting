<?php

namespace Http\Message\Authentication;

use Http\Message\Authentication;
use Psr\Http\Message\RequestInterface;

/**
 * Authenticate a PSR-7 Request by adding parameters to its query.
 *
 * Note: Although in some cases it can be useful, we do not recommend using query parameters for authentication.
 * Credentials in the URL is generally unsafe as they are not encrypted, anyone can see them.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class QueryParam implements Authentication
{
    /**
     * @var array
     */
    private $params = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(RequestInterface $request)
    {
        $uri = $request->getUri();
        $query = $uri->getQuery();
        $params = [];

        parse_str($query, $params);

        $params = array_merge($params, $this->params);

        $query = http_build_query($params, null, '&');

        $uri = $uri->withQuery($query);

        return $request->withUri($uri);
    }
}

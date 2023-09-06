<?php

declare(strict_types=1);

namespace Http\Client\Common\Plugin;

use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

/**
 * A plugin that helps you migrate from php-http/client-common 1.x to 2.x. This
 * will also help you to support PHP5 at the same time you support 2.x.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait VersionBridgePlugin
{
    abstract protected function doHandleRequest(RequestInterface $request, callable $next, callable $first);

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        return $this->doHandleRequest($request, $next, $first);
    }
}

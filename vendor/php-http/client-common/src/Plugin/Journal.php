<?php

declare(strict_types=1);

namespace Http\Client\Common\Plugin;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Records history of HTTP calls.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
interface Journal
{
    /**
     * Record a successful call.
     *
     * @param RequestInterface  $request  Request use to make the call
     * @param ResponseInterface $response Response returned by the call
     */
    public function addSuccess(RequestInterface $request, ResponseInterface $response);

    /**
     * Record a failed call.
     *
     * @param RequestInterface         $request   Request use to make the call
     * @param ClientExceptionInterface $exception Exception returned by the call
     */
    public function addFailure(RequestInterface $request, ClientExceptionInterface $exception);
}

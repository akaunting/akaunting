<?php

declare(strict_types=1);

namespace Http\Client\Common;

use Http\Client\Common\Exception\BatchException;
use Psr\Http\Message\RequestInterface;

/**
 * BatchClient allow to sends multiple request and retrieve a Batch Result.
 *
 * This implementation simply loops over the requests and uses sendRequest with each of them.
 *
 * @author Joel Wurtz <jwurtz@jolicode.com>
 */
interface BatchClientInterface
{
    /**
     * Send several requests.
     *
     * You may not assume that the requests are executed in a particular order. If the order matters
     * for your application, use sendRequest sequentially.
     *
     * @param RequestInterface[] $requests The requests to send
     *
     * @return BatchResult Containing one result per request
     *
     * @throws BatchException If one or more requests fails. The exception gives access to the
     *                        BatchResult with a map of request to result for success, request to
     *                        exception for failures
     */
    public function sendRequests(array $requests): BatchResult;
}

<?php

namespace Dingo\Api\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalHttpException extends HttpException
{
    /**
     * The response.
     *
     * @var \Illuminate\Http\Response
     */
    protected $response;

    /**
     * Create a new internal HTTP exception instance.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param string                                     $message
     * @param \Exception                                 $previous
     * @param array                                      $headers
     * @param int                                        $code
     *
     * @return void
     */
    public function __construct(Response $response, $message = null, Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->response = $response;

        parent::__construct($response->getStatusCode(), $message, $previous, $headers, $code);
    }

    /**
     * Get the response of the internal request.
     *
     * @return \Illuminate\Http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}

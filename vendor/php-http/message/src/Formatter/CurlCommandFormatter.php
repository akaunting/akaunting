<?php

namespace Http\Message\Formatter;

use Http\Message\Formatter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A formatter that prints a cURL command for HTTP requests.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class CurlCommandFormatter implements Formatter
{
    /**
     * {@inheritdoc}
     */
    public function formatRequest(RequestInterface $request)
    {
        $command = sprintf('curl %s', escapeshellarg((string) $request->getUri()->withFragment('')));
        if ('1.0' === $request->getProtocolVersion()) {
            $command .= ' --http1.0';
        } elseif ('2.0' === $request->getProtocolVersion()) {
            $command .= ' --http2';
        }

        $method = strtoupper($request->getMethod());
        if ('HEAD' === $method) {
            $command .= ' --head';
        } elseif ('GET' !== $method) {
            $command .= ' --request '.$method;
        }

        $command .= $this->getHeadersAsCommandOptions($request);

        $body = $request->getBody();
        if ($body->getSize() > 0) {
            // escapeshellarg argument max length on Windows, but longer body in curl command would be impractical anyways
            if ($body->getSize() > 8192) {
                $data = '[too long stream omitted]';
            } elseif ($body->isSeekable()) {
                $data = $body->__toString();
                $body->rewind();
                // all non-printable ASCII characters and <DEL> except for \t, \r, \n
                if (preg_match('/([\x00-\x09\x0C\x0E-\x1F\x7F])/', $data)) {
                    $data = '[binary stream omitted]';
                }
            } else {
                $data = '[non-seekable stream omitted]';
            }
            $escapedData = @escapeshellarg($data);
            if (empty($escapedData)) {
                $escapedData = 'We couldn\'t not escape the data properly';
            }

            $command .= sprintf(' --data %s', $escapedData);
        }

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function formatResponse(ResponseInterface $response)
    {
        return '';
    }

    /**
     * Formats a response in context of its request.
     *
     * @return string
     */
    public function formatResponseForRequest(ResponseInterface $response, RequestInterface $request)
    {
        return $this->formatResponse($response);
    }

    /**
     * @return string
     */
    private function getHeadersAsCommandOptions(RequestInterface $request)
    {
        $command = '';
        foreach ($request->getHeaders() as $name => $values) {
            if ('host' === strtolower($name) && $values[0] === $request->getUri()->getHost()) {
                continue;
            }

            if ('user-agent' === strtolower($name)) {
                $command .= sprintf(' -A %s', escapeshellarg($values[0]));

                continue;
            }

            $command .= sprintf(' -H %s', escapeshellarg($name.': '.$request->getHeaderLine($name)));
        }

        return $command;
    }
}

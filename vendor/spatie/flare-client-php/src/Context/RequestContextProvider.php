<?php

namespace Spatie\FlareClient\Context;

use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Exception\InvalidArgumentException;
use Throwable;

class RequestContextProvider implements ContextProvider
{
    protected ?Request $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?? Request::createFromGlobals();
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return [
            'url' => $this->request->getUri(),
            'ip' => $this->request->getClientIp(),
            'method' => $this->request->getMethod(),
            'useragent' => $this->request->headers->get('User-Agent'),
        ];
    }

    /**
     * @return array<int, mixed>
     */
    protected function getFiles(): array
    {
        if (is_null($this->request->files)) {
            return [];
        }

        return $this->mapFiles($this->request->files->all());
    }

    /**
     * @param array<int, mixed> $files
     *
     * @return array<string, string>
     */
    protected function mapFiles(array $files): array
    {
        return array_map(function ($file) {
            if (is_array($file)) {
                return $this->mapFiles($file);
            }

            if (! $file instanceof UploadedFile) {
                return;
            }

            try {
                $fileSize = $file->getSize();
            } catch (RuntimeException $e) {
                $fileSize = 0;
            }

            try {
                $mimeType = $file->getMimeType();
            } catch (InvalidArgumentException $e) {
                $mimeType = 'undefined';
            }

            return [
                'pathname' => $file->getPathname(),
                'size' => $fileSize,
                'mimeType' => $mimeType,
            ];
        }, $files);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSession(): array
    {
        try {
            $session = $this->request->getSession();
        } catch (Throwable $exception) {
            $session = [];
        }

        return $session ? $this->getValidSessionData($session) : [];
    }

    protected function getValidSessionData($session): array
    {
        if (! method_exists($session, 'all')) {
            return [];
        }

        try {
            json_encode($session->all());
        } catch (Throwable $e) {
            return [];
        }

        return $session->all();
    }

    /**
     * @return array<int|string, mixed
     */
    public function getCookies(): array
    {
        return $this->request->cookies->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function getHeaders(): array
    {
        /** @var array<string, list<string|null>> $headers */
        $headers = $this->request->headers->all();

        return array_filter(
            array_map(
                fn (array $header) => $header[0],
                $headers
            )
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequestData(): array
    {
        return [
            'queryString' => $this->request->query->all(),
            'body' => $this->request->request->all(),
            'files' => $this->getFiles(),
        ];
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'request' => $this->getRequest(),
            'request_data' => $this->getRequestData(),
            'headers' => $this->getHeaders(),
            'cookies' => $this->getCookies(),
            'session' => $this->getSession(),
        ];
    }
}

<?php
declare(strict_types=1);

namespace Plank\Mediable;

use Exception;
use Plank\Mediable\Exceptions\MediaUpload\FileExistsException;
use Plank\Mediable\Exceptions\MediaUpload\FileNotFoundException;
use Plank\Mediable\Exceptions\MediaUpload\FileNotSupportedException;
use Plank\Mediable\Exceptions\MediaUpload\FileSizeException;
use Plank\Mediable\Exceptions\MediaUpload\ForbiddenException;
use Plank\Mediable\Exceptions\MediaUploadException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HandlesMediaUploadExceptions
{
    /**
     * Table of HTTP status codes associated with the exception codes.
     *
     * @var array
     */
    private $status_codes = [
        // 403
        Response::HTTP_FORBIDDEN => [
            ForbiddenException::class,
        ],

        // 404
        Response::HTTP_NOT_FOUND => [
            FileNotFoundException::class,
        ],

        // 409
        Response::HTTP_CONFLICT => [
            FileExistsException::class,
        ],

        // 413
        Response::HTTP_REQUEST_ENTITY_TOO_LARGE => [
            FileSizeException::class,
        ],

        // 415
        Response::HTTP_UNSUPPORTED_MEDIA_TYPE => [
            FileNotSupportedException::class,
        ],
    ];

    /**
     * Transform a MediaUploadException into an HttpException.
     *
     * @param  \Exception $e
     * @return \Exception
     */
    protected function transformMediaUploadException(Exception $e): Exception
    {
        if ($e instanceof MediaUploadException) {
            $status_code = $this->getStatusCodeForMediaUploadException($e);
            return new HttpException($status_code, $e->getMessage(), $e);
        }

        return $e;
    }

    /**
     * Get the appropriate HTTP status code for the exception.
     *
     * @param  MediaUploadException $e
     * @return integer
     */
    private function getStatusCodeForMediaUploadException(MediaUploadException $e): int
    {
        foreach ($this->status_codes as $status_code => $exceptions) {
            if (in_array(get_class($e), $exceptions)) {
                return $status_code;
            }
        }

        return 500;
    }
}

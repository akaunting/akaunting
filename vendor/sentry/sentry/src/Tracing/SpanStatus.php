<?php

declare(strict_types=1);

namespace Sentry\Tracing;

final class SpanStatus implements \Stringable
{
    /**
     * @var string The value of the enum instance
     */
    private $value;

    /**
     * @var array<string, self>
     */
    private static $instances = [];

    /**
     * Constructor.
     *
     * @param string $value The value of the enum instance
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 401 Unauthorized (actually does mean unauthenticated according to RFC 7235).
     */
    public static function unauthenticated(): self
    {
        return self::getInstance('unauthenticated');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 403 Forbidden.
     */
    public static function permissionDenied(): self
    {
        return self::getInstance('permission_denied');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 404 Not Found.
     */
    public static function notFound(): self
    {
        return self::getInstance('not_found');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 409 Already exists.
     */
    public static function alreadyExists(): self
    {
        return self::getInstance('already_exists');
    }

    /**
     * Gets an instance of this enum representing the fact that the operation
     * was rejected because the system is not in a state required for the
     * operation.
     */
    public static function failedPrecondition(): self
    {
        return self::getInstance('failed_precondition');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 429 Too Many Requests.
     */
    public static function resourceExchausted(): self
    {
        return self::getInstance('resource_exhausted');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 501 Not Implemented.
     */
    public static function unimplemented(): self
    {
        return self::getInstance('unimplemented');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 503 Service Unavailable.
     */
    public static function unavailable(): self
    {
        return self::getInstance('unavailable');
    }

    /**
     * Gets an instance of this enum representing the fact that the deadline
     * expired before operation could complete.
     */
    public static function deadlineExceeded(): self
    {
        return self::getInstance('deadline_exceeded');
    }

    /**
     * Gets an instance of this enum representing the fact that the operation
     * completed successfully.
     */
    public static function ok(): self
    {
        return self::getInstance('ok');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 4xx as response status code.
     */
    public static function invalidArgument(): self
    {
        return self::getInstance('invalid_argument');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * 5xx as response status code.
     */
    public static function internalError(): self
    {
        return self::getInstance('internal_error');
    }

    /**
     * Gets an instance of this enum representing the fact that the server returned
     * with any non-standard HTTP status code.
     */
    public static function unknownError(): self
    {
        return self::getInstance('unknown_error');
    }

    /**
     * Returns an instance of this enum according to the given HTTP status code.
     *
     * @param int $statusCode The HTTP status code
     */
    public static function createFromHttpStatusCode(int $statusCode): self
    {
        switch (true) {
            case 401 === $statusCode:
                return self::unauthenticated();
            case 403 === $statusCode:
                return self::permissionDenied();
            case 404 === $statusCode:
                return self::notFound();
            case 409 === $statusCode:
                return self::alreadyExists();
            case 413 === $statusCode:
                return self::failedPrecondition();
            case 429 === $statusCode:
                return self::resourceExchausted();
            case 501 === $statusCode:
                return self::unimplemented();
            case 503 === $statusCode:
                return self::unavailable();
            case 504 === $statusCode:
                return self::deadlineExceeded();
            case $statusCode < 400:
                return self::ok();
            case $statusCode < 500:
                return self::invalidArgument();
            case $statusCode < 600:
                return self::internalError();
            default:
                return self::unknownError();
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function getInstance(string $value): self
    {
        if (!isset(self::$instances[$value])) {
            self::$instances[$value] = new self($value);
        }

        return self::$instances[$value];
    }
}

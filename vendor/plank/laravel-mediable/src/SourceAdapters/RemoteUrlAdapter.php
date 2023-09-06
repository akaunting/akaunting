<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use Plank\Mediable\Helpers\File;

/**
 * URL Adapter.
 *
 * Adapts a string representing a URL
 */
class RemoteUrlAdapter implements SourceAdapterInterface
{
    /**
     * Cache of headers loaded from the remote server.
     * @var array
     */
    private $headers;

    /**
     * The source string.
     * @var string
     */
    protected $source;

    /**
     * Constructor.
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function path(): string
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function filename(): string
    {
        return pathinfo(parse_url($this->source, PHP_URL_PATH), PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function extension(): string
    {
        $extension = pathinfo(parse_url($this->source, PHP_URL_PATH), PATHINFO_EXTENSION);

        if ($extension) {
            return $extension;
        }

        return (string)File::guessExtension($this->mimeType());
    }

    /**
     * {@inheritdoc}
     */
    public function mimeType(): string
    {
        return $this->getHeader('Content-Type');
    }

    /**
     * {@inheritdoc}
     */
    public function contents(): string
    {
        return (string)file_get_contents($this->source);
    }

    /**
     * @inheritdoc
     */
    public function getStreamResource()
    {
        return fopen($this->source, 'rb');
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return strpos((string)$this->getHeader(0), '200') !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return (int)$this->getHeader('Content-Length');
    }

    /**
     * Read a header value by name from the remote content.
     *
     * @param  string|int $key Header name
     * @return string|null
     */
    private function getHeader($key, $default = null): ?string
    {
        if (!$this->headers) {
            $this->headers = $this->getHeaders();
        }
        if (array_key_exists($key, $this->headers)) {
            //if redirects encountered, return the final values
            if (is_array($this->headers[$key])) {
                return end($this->headers[$key]);
            } else {
                return $this->headers[$key];
            }
        }

        return null;
    }

    /**
     * Read all the headers from the remote content.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        $headers = @get_headers(
            $this->source,
            version_compare(phpversion(), '8.0.0', '>=') ? true : 1
        );

        return $headers ?: [];
    }
}

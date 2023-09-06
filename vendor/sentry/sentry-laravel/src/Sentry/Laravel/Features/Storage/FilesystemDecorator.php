<?php

namespace Sentry\Laravel\Features\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Sentry\Breadcrumb;
use Sentry\Laravel\Integration;
use Sentry\Laravel\Util\Filesize;
use Sentry\Tracing\SpanContext;
use function Sentry\trace;

/**
 * Decorates the underlying filesystem by wrapping all calls to it with tracing.
 *
 * Parameters such as paths, directories or options are attached to the span as data,
 * parameters that contain file contents are omitted due to potential problems with
 * payload size or sensitive data.
 */
trait FilesystemDecorator
{
    /** @var Filesystem */
    protected $filesystem;

    /** @var array */
    protected $defaultData;

    /** @var bool */
    protected $recordSpans;

    /** @var bool */
    protected $recordBreadcrumbs;

    /**
     * Execute the method on the underlying filesystem and wrap it with tracing and log a breadcrumb.
     *
     * @param list<mixed> $args
     * @param array<string, mixed> $data
     *
     * @return mixed
     */
    protected function withSentry(string $method, array $args, ?string $description, array $data)
    {
        $op = "file.{$method}"; // See https://develop.sentry.dev/sdk/performance/span-operations/#web-server
        $data = array_merge($data, $this->defaultData);

        if ($this->recordBreadcrumbs) {
            Integration::addBreadcrumb(new Breadcrumb(
                Breadcrumb::LEVEL_INFO,
                Breadcrumb::TYPE_DEFAULT,
                $op,
                $description,
                $data
            ));
        }

        if ($this->recordSpans) {
            $spanContext = new SpanContext;
            $spanContext->setOp($op);
            $spanContext->setData($data);
            $spanContext->setDescription($description);

            return trace(function () use ($method, $args) {
                return $this->filesystem->{$method}(...$args);
            }, $spanContext);
        }

        return $this->filesystem->{$method}(...$args);
    }

    public function exists($path)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path'));
    }

    public function get($path)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path'));
    }

    public function readStream($path)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path'));
    }

    public function put($path, $contents, $options = [])
    {
        $description = is_string($contents) ? sprintf('%s (%s)', $path, Filesize::toHuman(strlen($contents))) : $path;

        return $this->withSentry(__FUNCTION__, func_get_args(), $description, compact('path', 'options'));
    }

    public function writeStream($path, $resource, array $options = [])
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path', 'options'));
    }

    public function getVisibility($path)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path'));
    }

    public function setVisibility($path, $visibility)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path', 'visibility'));
    }

    public function prepend($path, $data, $separator = PHP_EOL)
    {
        $description = is_string($data) ? sprintf('%s (%s)', $path, Filesize::toHuman(strlen($data))) : $path;

        return $this->withSentry(__FUNCTION__, func_get_args(), $description, compact('path'));
    }

    public function append($path, $data, $separator = PHP_EOL)
    {
        $description = is_string($data) ? sprintf('%s (%s)', $path, Filesize::toHuman(strlen($data))) : $path;

        return $this->withSentry(__FUNCTION__, func_get_args(), $description, compact('path'));
    }

    public function delete($paths)
    {
        if (is_array($paths)) {
            $data = compact('paths');
            $description = sprintf('%s paths', count($paths));
        } else {
            $data = ['path' => $paths];
            $description = $paths;
        }

        return $this->withSentry(__FUNCTION__, func_get_args(), $description, $data);
    }

    public function copy($from, $to)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), sprintf('from "%s" to "%s"', $from, $to), compact('from', 'to'));
    }

    public function move($from, $to)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), sprintf('from "%s" to "%s"', $from, $to), compact('from', 'to'));
    }

    public function size($path)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path'));
    }

    public function lastModified($path)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path'));
    }

    public function files($directory = null, $recursive = false)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $directory, compact('directory', 'recursive'));
    }

    public function allFiles($directory = null)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $directory, compact('directory'));
    }

    public function directories($directory = null, $recursive = false)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $directory, compact('directory', 'recursive'));
    }

    public function allDirectories($directory = null)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $directory, compact('directory'));
    }

    public function makeDirectory($path)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $path, compact('path'));
    }

    public function deleteDirectory($directory)
    {
        return $this->withSentry(__FUNCTION__, func_get_args(), $directory, compact('directory'));
    }

    public function __call($name, $arguments)
    {
        return $this->filesystem->{$name}(...$arguments);
    }
}

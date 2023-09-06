<?php

namespace Sentry\Laravel\Tracing;

use Sentry\Frame;
use Sentry\Options;
use Sentry\FrameBuilder;
use Illuminate\Support\Str;
use Sentry\Serializer\RepresentationSerializerInterface;

/**
 * @internal
 */
class BacktraceHelper
{
    /**
     * @var Options The SDK client options
     */
    private $options;

    /**
     * @var FrameBuilder An instance of the builder of {@see Frame} objects
     */
    private $frameBuilder;

    /**
     * Constructor.
     *
     * @param Options                           $options                  The SDK client options
     * @param RepresentationSerializerInterface $representationSerializer The representation serializer
     */
    public function __construct(Options $options, RepresentationSerializerInterface $representationSerializer)
    {
        $this->options = $options;
        $this->frameBuilder = new FrameBuilder($options, $representationSerializer);
    }

    /**
     * Find the first in app frame for a given backtrace.
     *
     * @param array<int, array<string, mixed>> $backtrace The backtrace
     *
     * @phpstan-param list<array{
     *     line?: integer,
     *     file?: string,
     * }> $backtrace
     */
    public function findFirstInAppFrameForBacktrace(array $backtrace): ?Frame
    {
        $file = Frame::INTERNAL_FRAME_FILENAME;
        $line = 0;

        foreach ($backtrace as $backtraceFrame) {
            $frame = $this->frameBuilder->buildFromBacktraceFrame($file, $line, $backtraceFrame);

            if ($frame->isInApp()) {
                return $frame;
            }

            $file = $backtraceFrame['file'] ?? Frame::INTERNAL_FRAME_FILENAME;
            $line = $backtraceFrame['line'] ?? 0;
        }

        return null;
    }

    /**
     * Takes a frame and if it's a compiled view path returns the original view path.
     *
     * @param \Sentry\Frame $frame
     *
     * @return string|null
     */
    public function getOriginalViewPathForFrameOfCompiledViewPath(Frame $frame): ?string
    {
        // Check if we are dealing with a frame for a cached view path
        if (!Str::startsWith($frame->getFile(), '/storage/framework/views/')) {
            return null;
        }

        // If for some reason the file does not exists, skip resolving
        if (!file_exists($frame->getAbsoluteFilePath())) {
            return null;
        }

        $viewFileContents = file_get_contents($frame->getAbsoluteFilePath());

        preg_match('/PATH (?<originalPath>.*?) ENDPATH/', $viewFileContents, $matches);

        // No path comment found in the file, must be a very old Laravel version
        if (empty($matches['originalPath'])) {
            return null;
        }

        return $this->stripPrefixFromFilePath($matches['originalPath']);
    }

    /**
     * Removes from the given file path the specified prefixes.
     *
     * @param string $filePath The path to the file
     */
    private function stripPrefixFromFilePath(string $filePath): string
    {
        foreach ($this->options->getPrefixes() as $prefix) {
            if (Str::startsWith($filePath, $prefix)) {
                return mb_substr($filePath, mb_strlen($prefix));
            }
        }

        return $filePath;
    }
}

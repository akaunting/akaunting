<?php

declare(strict_types=1);

namespace Sentry;

use Sentry\Serializer\RepresentationSerializerInterface;
use Sentry\Util\PrefixStripper;

/**
 * This class builds a {@see Frame} object out of a backtrace's raw frame.
 *
 * @internal
 *
 * @psalm-type StacktraceFrame array{
 *     function?: string,
 *     line?: int,
 *     file?: string,
 *     class?: class-string,
 *     type?: string,
 *     args?: mixed[]
 * }
 */
final class FrameBuilder
{
    use PrefixStripper;

    /**
     * @var Options The SDK client options
     */
    private $options;

    /**
     * @var RepresentationSerializerInterface The representation serializer
     */
    private $representationSerializer;

    /**
     * Constructor.
     *
     * @param Options                           $options                  The SDK client options
     * @param RepresentationSerializerInterface $representationSerializer The representation serializer
     */
    public function __construct(Options $options, RepresentationSerializerInterface $representationSerializer)
    {
        $this->options = $options;
        $this->representationSerializer = $representationSerializer;
    }

    /**
     * Builds a {@see Frame} object from the given backtrace's raw frame.
     *
     * @param string               $file           The file where the frame originated
     * @param int                  $line           The line at which the frame originated
     * @param array<string, mixed> $backtraceFrame The raw frame
     *
     * @psalm-param StacktraceFrame $backtraceFrame
     */
    public function buildFromBacktraceFrame(string $file, int $line, array $backtraceFrame): Frame
    {
        // The filename can be in any of these formats:
        //   - </path/to/filename>
        //   - </path/to/filename>(<line number>) : eval()'d code
        //   - </path/to/filename>(<line number>) : runtime-created function
        if (preg_match('/^(.*)\((\d+)\) : (?:eval\(\)\'d code|runtime-created function)$/', $file, $matches)) {
            $file = $matches[1];
            $line = (int) $matches[2];
        }

        $functionName = null;
        $rawFunctionName = null;
        $strippedFilePath = $this->stripPrefixFromFilePath($this->options, $file);

        if (isset($backtraceFrame['class']) && isset($backtraceFrame['function'])) {
            $functionName = $backtraceFrame['class'];

            if (str_starts_with($functionName, Frame::ANONYMOUS_CLASS_PREFIX)) {
                $functionName = Frame::ANONYMOUS_CLASS_PREFIX . $this->stripPrefixFromFilePath($this->options, substr($backtraceFrame['class'], \strlen(Frame::ANONYMOUS_CLASS_PREFIX)));
            }

            $rawFunctionName = sprintf('%s::%s', $backtraceFrame['class'], $backtraceFrame['function']);
            $functionName = sprintf('%s::%s', preg_replace('/(?::\d+\$|0x)[a-fA-F0-9]+$/', '', $functionName), $backtraceFrame['function']);
        } elseif (isset($backtraceFrame['function'])) {
            $functionName = $backtraceFrame['function'];
        }

        return new Frame(
            $functionName,
            $strippedFilePath,
            $line,
            $rawFunctionName,
            Frame::INTERNAL_FRAME_FILENAME !== $file ? $file : null,
            $this->getFunctionArguments($backtraceFrame),
            $this->isFrameInApp($file, $functionName)
        );
    }

    /**
     * Checks whether a certain frame should be marked as "in app" or not.
     *
     * @param string      $file         The file to check
     * @param string|null $functionName The name of the function
     */
    private function isFrameInApp(string $file, ?string $functionName): bool
    {
        if (Frame::INTERNAL_FRAME_FILENAME === $file) {
            return false;
        }

        if (null !== $functionName && str_starts_with($functionName, 'Sentry\\')) {
            return false;
        }

        $excludedAppPaths = $this->options->getInAppExcludedPaths();
        $includedAppPaths = $this->options->getInAppIncludedPaths();
        $absoluteFilePath = @realpath($file) ?: $file;
        $isInApp = true;

        foreach ($excludedAppPaths as $excludedAppPath) {
            if (str_starts_with($absoluteFilePath, $excludedAppPath)) {
                $isInApp = false;

                break;
            }
        }

        foreach ($includedAppPaths as $includedAppPath) {
            if (str_starts_with($absoluteFilePath, $includedAppPath)) {
                $isInApp = true;

                break;
            }
        }

        return $isInApp;
    }

    /**
     * Gets the arguments of the function called in the given frame.
     *
     * @param array<string, mixed> $backtraceFrame The frame data
     *
     * @return array<string, mixed>
     *
     * @psalm-param StacktraceFrame $backtraceFrame
     */
    private function getFunctionArguments(array $backtraceFrame): array
    {
        if (!isset($backtraceFrame['function'], $backtraceFrame['args'])) {
            return [];
        }

        $reflectionFunction = null;

        try {
            if (isset($backtraceFrame['class'])) {
                if (method_exists($backtraceFrame['class'], $backtraceFrame['function'])) {
                    $reflectionFunction = new \ReflectionMethod($backtraceFrame['class'], $backtraceFrame['function']);
                } elseif (isset($backtraceFrame['type']) && '::' === $backtraceFrame['type']) {
                    $reflectionFunction = new \ReflectionMethod($backtraceFrame['class'], '__callStatic');
                } else {
                    $reflectionFunction = new \ReflectionMethod($backtraceFrame['class'], '__call');
                }
            } elseif (!\in_array($backtraceFrame['function'], ['{closure}', '__lambda_func'], true) && \function_exists($backtraceFrame['function'])) {
                $reflectionFunction = new \ReflectionFunction($backtraceFrame['function']);
            }
        } catch (\ReflectionException $e) {
            // Reflection failed, we do nothing instead
        }

        $argumentValues = [];

        if (null !== $reflectionFunction) {
            $argumentValues = $this->getFunctionArgumentValues($reflectionFunction, $backtraceFrame['args']);
        } else {
            foreach ($backtraceFrame['args'] as $parameterPosition => $parameterValue) {
                $argumentValues['param' . $parameterPosition] = $parameterValue;
            }
        }

        foreach ($argumentValues as $argumentName => $argumentValue) {
            $argumentValues[$argumentName] = $this->representationSerializer->representationSerialize($argumentValue);
        }

        return $argumentValues;
    }

    /**
     * Gets an hashmap indexed by argument name containing all the arguments
     * passed to the function called in the given frame of the stacktrace.
     *
     * @param \ReflectionFunctionAbstract $reflectionFunction A reflection object
     * @param mixed[]                     $backtraceFrameArgs The arguments of the frame
     *
     * @return array<string, mixed>
     */
    private function getFunctionArgumentValues(\ReflectionFunctionAbstract $reflectionFunction, array $backtraceFrameArgs): array
    {
        $argumentValues = [];

        foreach ($reflectionFunction->getParameters() as $reflectionParameter) {
            $parameterPosition = $reflectionParameter->getPosition();

            if (!isset($backtraceFrameArgs[$parameterPosition])) {
                continue;
            }

            $argumentValues[$reflectionParameter->getName()] = $backtraceFrameArgs[$parameterPosition];
        }

        return $argumentValues;
    }
}

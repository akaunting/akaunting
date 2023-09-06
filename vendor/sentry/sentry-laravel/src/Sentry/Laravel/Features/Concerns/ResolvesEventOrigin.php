<?php

namespace Sentry\Laravel\Features\Concerns;

use Illuminate\Contracts\Container\Container;
use Sentry\Laravel\Tracing\BacktraceHelper;

trait ResolvesEventOrigin
{
    protected function container(): Container
    {
        return app();
    }

    protected function resolveEventOrigin(): ?string
    {
        $backtraceHelper = $this->makeBacktraceHelper();

        $firstAppFrame = $backtraceHelper->findFirstInAppFrameForBacktrace(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        if ($firstAppFrame === null) {
            return null;
        }

        $filePath = $backtraceHelper->getOriginalViewPathForFrameOfCompiledViewPath($firstAppFrame) ?? $firstAppFrame->getFile();

        return "{$filePath}:{$firstAppFrame->getLine()}";
    }

    private function makeBacktraceHelper(): BacktraceHelper
    {
        return $this->container()->make(BacktraceHelper::class);
    }
}

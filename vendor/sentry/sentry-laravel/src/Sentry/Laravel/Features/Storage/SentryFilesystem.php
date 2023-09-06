<?php

namespace Sentry\Laravel\Features\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;

class SentryFilesystem implements Filesystem
{
    use FilesystemDecorator;

    public function __construct(Filesystem $filesystem, array $defaultData, bool $recordSpans, bool $recordBreadcrumbs)
    {
        $this->filesystem = $filesystem;
        $this->defaultData = $defaultData;
        $this->recordSpans = $recordSpans;
        $this->recordBreadcrumbs = $recordBreadcrumbs;
    }
}

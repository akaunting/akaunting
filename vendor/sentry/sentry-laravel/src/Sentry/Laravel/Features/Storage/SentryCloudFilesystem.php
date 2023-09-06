<?php

namespace Sentry\Laravel\Features\Storage;

use Illuminate\Contracts\Filesystem\Cloud;

class SentryCloudFilesystem implements Cloud
{
    use CloudFilesystemDecorator;

    public function __construct(Cloud $filesystem, array $defaultData, bool $recordSpans, bool $recordBreadcrumbs)
    {
        $this->filesystem = $filesystem;
        $this->defaultData = $defaultData;
        $this->recordSpans = $recordSpans;
        $this->recordBreadcrumbs = $recordBreadcrumbs;
    }
}

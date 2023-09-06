<?php

namespace Sentry\Laravel\Features\Storage;

use Illuminate\Filesystem\FilesystemAdapter;

class SentryFilesystemAdapter extends FilesystemAdapter
{
    use FilesystemAdapterDecorator;

    public function __construct(FilesystemAdapter $filesystem, array $defaultData, bool $recordSpans, bool $recordBreadcrumbs)
    {
        parent::__construct($filesystem->getDriver(), $filesystem->getAdapter(), $filesystem->getConfig());

        $this->filesystem = $filesystem;
        $this->defaultData = $defaultData;
        $this->recordSpans = $recordSpans;
        $this->recordBreadcrumbs = $recordBreadcrumbs;
    }
}

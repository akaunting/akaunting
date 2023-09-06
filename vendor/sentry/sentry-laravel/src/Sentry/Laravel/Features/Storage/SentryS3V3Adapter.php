<?php

namespace Sentry\Laravel\Features\Storage;

use Illuminate\Filesystem\AwsS3V3Adapter;

class SentryS3V3Adapter extends AwsS3V3Adapter
{
    use FilesystemAdapterDecorator;

    public function __construct(AwsS3V3Adapter $filesystem, array $defaultData, bool $recordSpans, bool $recordBreadcrumbs)
    {
        parent::__construct($filesystem->getDriver(), $filesystem->getAdapter(), $filesystem->getConfig(), $filesystem->getClient());

        $this->filesystem = $filesystem;
        $this->defaultData = $defaultData;
        $this->recordSpans = $recordSpans;
        $this->recordBreadcrumbs = $recordBreadcrumbs;
    }
}

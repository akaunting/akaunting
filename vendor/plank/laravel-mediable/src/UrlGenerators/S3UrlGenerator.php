<?php
declare(strict_types=1);

namespace Plank\Mediable\UrlGenerators;

use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Arr;
use Plank\Mediable\Helpers\File;

class S3UrlGenerator extends BaseUrlGenerator implements TemporaryUrlGeneratorInterface
{
    /**
     * Filesystem Manager.
     * @var FilesystemManager
     */
    protected $filesystem;

    /**
     * Constructor.
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Filesystem\FilesystemManager $filesystem
     */
    public function __construct(Config $config, FilesystemManager $filesystem)
    {
        parent::__construct($config);
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getAbsolutePath(): string
    {
        return $this->getUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(): string
    {
        /** @var Cloud $filesystem */
        $filesystem = $this->filesystem->disk($this->media->disk);
        return $filesystem->url($this->media->getDiskPath());
    }

    public function getTemporaryUrl(\DateTimeInterface $expiry): string
    {
        $filesystem = $this->filesystem->disk($this->media->disk);

        // Laravel 9+ / Flysystem 2+
        if (method_exists($filesystem, 'temporaryUrl')) {
            return $filesystem->temporaryUrl($this->media->getDiskPath(), $expiry);
        }

        // Earlier versions
        $root = config("filesystems.disks.{$this->media->disk}.root", '');
        $adapter = $filesystem->getDriver()->getAdapter();
        $command = $adapter->getClient()->getCommand(
            'GetObject',
            [
                'Bucket' => $adapter->getBucket(),
                'Key' => File::joinPathComponents($root, $this->media->getDiskPath()),
            ]
        );

        return (string)$adapter->getClient()->createPresignedRequest(
            $command,
            $expiry
        )->getUri();
    }
}

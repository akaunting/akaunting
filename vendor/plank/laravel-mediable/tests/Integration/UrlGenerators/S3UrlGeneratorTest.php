<?php

namespace Plank\Mediable\Tests\Integration\UrlGenerators;

use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemManager;
use Plank\Mediable\Media;
use Plank\Mediable\Tests\TestCase;
use Plank\Mediable\UrlGenerators\S3UrlGenerator;

use function GuzzleHttp\Psr7\parse_query;

class S3UrlGeneratorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setup();
        if (!$this->s3ConfigLoaded()) {
            $this->markTestSkipped('S3 Credentials not available.');
        }
    }

    public function tearDown(): void
    {
        $filesystemManager = app(FilesystemManager::class);
        $filesystemManager->disk('s3')
            ->delete($this->getMedia()->getDiskPath());

        parent::tearDown();
    }

    public function test_it_generates_absolute_path()
    {
        $generator = $this->setupGenerator();
        $this->assertEquals(
            sprintf(
                'https://%s.s3.%s.amazonaws.com/%s/foo/bar.jpg',
                env('S3_BUCKET'),
                env('S3_REGION'),
                config('filesystems.disks.s3.root')
            ),
            $generator->getAbsolutePath()
        );
    }

    public function test_it_generates_url()
    {
        $generator = $this->setupGenerator();
        $this->assertEquals(
            sprintf(
                'https://%s.s3.%s.amazonaws.com/%s/foo/bar.jpg',
                env('S3_BUCKET'),
                env('S3_REGION'),
                config('filesystems.disks.s3.root')
            ),
            $generator->getUrl()
        );
    }

    public function test_it_generates_temporary_url()
    {
        $generator = $this->setupGenerator();
        $url = $generator->getTemporaryUrl(Carbon::now()->addDay());
        [$uri, $queryString] = explode('?', $url);
        $this->assertEquals(
            sprintf(
                'https://%s.s3.%s.amazonaws.com/%s/foo/bar.jpg',
                env('S3_BUCKET'),
                env('S3_REGION'),
                config('filesystems.disks.s3.root')
            ),
            $uri
        );
        parse_str($queryString, $queryParams);
        $this->assertArrayHasKey(
            'X-Amz-Credential',
            $queryParams
        );
        $this->assertArrayHasKey(
            'X-Amz-Expires',
            $queryParams
        );
        $this->assertArrayHasKey(
            'X-Amz-Algorithm',
            $queryParams
        );
        $this->assertArrayHasKey(
            'X-Amz-Signature',
            $queryParams
        );
    }

    protected function setupGenerator()
    {
        $media = $this->getMedia();
        $this->useFilesystem('s3');
        $filesystemManager = app(FilesystemManager::class);
        $filesystemManager->disk('s3')
            ->put(
                $media->getDiskPath(),
                file_get_contents(dirname(__DIR__, 2) . '/_data/plank.png')
            );
        $generator = new S3UrlGenerator(config(), $filesystemManager);
        $generator->setMedia($media);
        return $generator;
    }

    private function getMedia(): Media
    {
        return $this->makeMedia(
            [
                'disk' => 's3',
                'directory' => 'foo',
                'filename' => 'bar',
                'extension' => 'jpg'
            ]
        );
    }
}

<?php

namespace Plank\Mediable\Tests\Integration\SourceAdapters;

use Plank\Mediable\SourceAdapters\FileAdapter;
use Plank\Mediable\SourceAdapters\LocalPathAdapter;
use Plank\Mediable\SourceAdapters\RawContentAdapter;
use Plank\Mediable\SourceAdapters\RemoteUrlAdapter;
use Plank\Mediable\SourceAdapters\SourceAdapterInterface;
use Plank\Mediable\SourceAdapters\StreamAdapter;
use Plank\Mediable\SourceAdapters\StreamResourceAdapter;
use Plank\Mediable\SourceAdapters\UploadedFileAdapter;
use Plank\Mediable\Stream;
use Plank\Mediable\Tests\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SourceAdapterTest extends TestCase
{
    private const READABLE_MODES = [
        'r' => true,
        'w+' => true,
        'r+' => true,
        'x+' => true,
        'c+' => true,
        'rb' => true,
        'w+b' => true,
        'r+b' => true,
        'x+b' => true,
        'c+b' => true,
        'rt' => true,
        'w+t' => true,
        'r+t' => true,
        'x+t' => true,
        'c+t' => true,
        'a+' => true
    ];

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['filesystem']->disk('uploads')->put('plank.png', $this->sampleFile());
    }

    public function adapterProvider()
    {
        $file = $this->sampleFilePath();
        $string = file_get_contents($file);
        $url = $this->remoteFilePath() . '?foo=bar.baz';

        $uploadedFile = new UploadedFile(
            $file,
            'plank.png',
            'image/png',
            UPLOAD_ERR_OK,
            true
        );

        $fileResource = fopen($file, 'rb');
        $fileStream = new Stream(fopen($file, 'rb'));

        $httpResource = fopen($url, 'rb');
        $httpStream = new Stream(fopen($url, 'rb'));

        $memoryResource = fopen('php://memory', 'w+b');
        fwrite($memoryResource, $string);
        rewind($memoryResource);

        $memoryStream = new Stream(fopen('php://memory', 'w+b'));
        $memoryStream->write($string);

        $data = [
            'FileAdapter' => [FileAdapter::class, new File($file), $file, 'plank'],
            'UploadedFileAdapter' => [
                UploadedFileAdapter::class,
                $uploadedFile,
                $file,
                'plank'
            ],
            'LocalPathAdapter' => [LocalPathAdapter::class, $file, $file, 'plank'],
            'RemoteUrlAdapter' => [RemoteUrlAdapter::class, $url, $url, 'plank'],
            'RawContentAdapter' => [RawContentAdapter::class, $string, null, '', false],
            'StreamResourceAdapter_Local' => [
                StreamResourceAdapter::class,
                $fileResource,
                $file,
                'plank'
            ],
            'StreamAdapter_Local' => [
                StreamAdapter::class,
                $fileStream,
                $file,
                'plank',
                false
            ],
            'StreamResourceAdapter_Remote' => [
                StreamResourceAdapter::class,
                $httpResource,
                $url,
                'plank'
            ],
            'StreamAdapter_Remote' => [
                StreamAdapter::class,
                $httpStream,
                $url,
                'plank',
                false
            ],
            'StreamResourceAdapter_Memory' => [
                StreamResourceAdapter::class,
                $memoryResource,
                'php://memory',
                ''
            ],
            'StreamAdapter_Memory' => [
                StreamAdapter::class,
                $memoryStream,
                'php://memory',
                ''
            ],
        ];
        return $data;
    }

    public function invalidAdapterProvider()
    {
        $file = __DIR__ . '/../../_data/invalid.png';
        $url = 'https://raw.githubusercontent.com/plank/laravel-mediable/master/tests/_data/invalid.png';

        $uploadedFile = new UploadedFile(
            $file,
            'invalid.png',
            'image/png',
            UPLOAD_ERR_CANT_WRITE,
            false
        );

        return [
            [new FileAdapter(new File($file, false))],
            [new LocalPathAdapter($file)],
            [new RemoteUrlAdapter($url)],
            [new RemoteUrlAdapter('http://example.invalid')],
            [new UploadedFileAdapter($uploadedFile)],
            [new StreamResourceAdapter(fopen($this->sampleFilePath(), 'a'))],
            [new StreamResourceAdapter(fopen('php://stdin', 'w'))],
        ];
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_can_return_source($adapterClass, $source)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $this->assertEquals($source, $adapter->getSource());
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_adapts_absolute_path($adapterClass, $source, $path)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $this->assertEquals($path, $adapter->path());
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_adapts_filename($adapterClass, $source, $path, $filename)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $this->assertSame($filename, $adapter->filename());
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_adapts_extension($adapterClass, $source)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $this->assertEquals('png', $adapter->extension());
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_adapts_mime_type($adapterClass, $source)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $this->assertEquals('image/png', $adapter->mimeType());
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_adapts_file_contents($adapterClass, $source)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $this->assertIsString($adapter->contents());
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_adapts_to_stream($adapterClass, $source)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $stream = $adapter->getStreamResource();
        try {
            $this->assertTrue(is_resource($stream));
            $metadata = stream_get_meta_data($stream);
            $this->assertArrayHasKey($metadata['mode'], self::READABLE_MODES);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_adapts_file_size($adapterClass, $source)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $this->assertEquals(7173, $adapter->size());
    }

    /**
     * @dataProvider adapterProvider
     */
    public function test_it_verifies_file_validity($adapterClass, $source)
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = new $adapterClass($source);
        $this->assertTrue($adapter->valid());
    }

    /**
     * @dataProvider invalidAdapterProvider
     */
    public function test_it_verifies_file_validity_failure(
        SourceAdapterInterface $adapter
    ) {
        $this->assertFalse($adapter->valid());
    }
}

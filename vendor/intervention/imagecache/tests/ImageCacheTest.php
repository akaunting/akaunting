<?php

namespace Intervention\Image\Test;

use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\Image;
use Intervention\Image\ImageCache;
use Intervention\Image\ImageManager;
use PHPUnit\Framework\TestCase;

class ImageCacheTest extends TestCase
{
    public function tearDown(): void
    {
        $this->emptyCacheDirectory();
    }

    private function emptyCacheDirectory()
    {
        $files = new Filesystem();

        foreach ($files->directories('storage/cache') as $directory) {
            $files->deleteDirectory($directory);
        }
    }

    public function testConstructor()
    {
        $img = new ImageCache();
        $this->assertInstanceOf(ImageCache::class, $img);
        $this->assertInstanceOf(ImageManager::class, $img->manager);
        $this->assertInstanceOf(Repository::class, $img->cache);
    }

    public function testConstructorWithInjection()
    {
        // add new default cache
        $manager = $this->createMock(ImageManager::class);
        $cache = $this->createMock(Repository::class);

        $img = new ImageCache($manager, $cache);
        $this->assertInstanceOf(ImageCache::class, $img);
        $this->assertInstanceOf(ImageManager::class, $img->manager);
        $this->assertInstanceOf(Repository::class, $img->cache);
    }

    public function testMagicMethodCalls()
    {
        $img = new ImageCache();

        $img->test1(1, 2, 3);
        $img->test2(null);
        $img->test3([1, 2, 3]);

        $this->assertIsArray($img->calls);
        $this->assertEquals(count($img->calls), 3);
        $this->assertIsString($img->calls[0]['name']);
        $this->assertIsString($img->calls[1]['name']);
        $this->assertIsString($img->calls[2]['name']);
        $this->assertIsArray($img->calls[0]['arguments']);
        $this->assertIsArray($img->calls[1]['arguments']);
        $this->assertIsArray($img->calls[2]['arguments']);
        $this->assertEquals($img->calls[0]['name'], 'test1');
        $this->assertEquals($img->calls[1]['name'], 'test2');
        $this->assertEquals($img->calls[2]['name'], 'test3');
        $this->assertEquals($img->calls[0]['arguments'][0], 1);
        $this->assertEquals($img->calls[0]['arguments'][1], 2);
        $this->assertEquals($img->calls[0]['arguments'][2], 3);
        $this->assertTrue(is_null($img->calls[1]['arguments'][0]));
        $this->assertIsArray($img->calls[2]['arguments'][0]);
        $this->assertEquals($img->calls[2]['arguments'][0][0], 1);
        $this->assertEquals($img->calls[2]['arguments'][0][1], 2);
        $this->assertEquals($img->calls[2]['arguments'][0][2], 3);
    }

    public function testChecksum()
    {
        // checksum of empty image
        $sum = 'ad7b81dea42cf2ef7525c274471e3ce6';
        $img = new ImageCache();
        $this->assertEquals($sum, $img->checksum());

        // checksum of test image resized to 300x200
        $sum = '9b3c716836cb438c4619eb0452740019';
        $img = new ImageCache();
        $img->make('foo/bar.jpg');
        $img->resize(300, 200);
        $this->assertEquals($sum, $img->checksum());
    }

    public function testChecksumWithClosure()
    {
        // closure must be serializable
        $sum = '639d464bbda684f3d56410d1715076ba';
        $img = new ImageCache();
        $img->canvas(300, 200, 'fff');
        $img->text('foo', 0, 0, function ($font) {
            $font->valign('top');
            $font->size(32);
        });
        $this->assertEquals($img->checksum(), $sum);

        // checksum must differ, if values in closure change
        $sum = '8b76a94baf81bb6f49451635612aeba5';
        $img = new ImageCache();
        $img->canvas(300, 200, 'fff');
        $img->text('foo', 0, 0, function ($font) {
            $font->valign('top');
            $font->size(30);
        });
        $this->assertEquals($img->checksum(), $sum);
    }

    public function testChecksumWithProperty()
    {
        // checksum of test image resized to 300x200
        $sum = '9b3c716836cb438c4619eb0452740019';
        $img = new ImageCache();
        $img->make('foo/bar.jpg');
        $img->resize(300, 200);
        $this->assertEquals($sum, $img->checksum());

        // different checksum with property
        $sum = '299243f98e2de9939ea59e5884ab1b8b';
        $img = new ImageCache();
        $img->setProperty('foo', 'bar');
        $img->make('foo/bar.jpg');
        $img->resize(300, 200);
        $this->assertEquals($sum, $img->checksum());
    }

    public function testProcess()
    {
        $image = $this->getMockBuilder(Image::class)
                      ->setMethods(['resize', 'blur'])
                      ->getMock();

        $manager = $this->getMockBuilder(ImageManager::class)
                        ->setMethods(['make'])
                        ->getMock();

        $image->expects($this->once())
              ->method('resize')
              ->with($this->equalTo(300), $this->equalTo(200))
              ->willReturn($image);

        $image->expects($this->once())
              ->method('blur')
              ->with($this->equalTo(2))
              ->willReturn($image);

        $manager->expects($this->once())
                ->method('make')
                ->with($this->equalTo('foo/bar.jpg'))
                ->willReturn($image);

        $cache = $this->createMock(Repository::class);

        $img = new ImageCache($manager, $cache);
        $img->make('foo/bar.jpg');
        $img->resize(300, 200);
        $img->blur(2);
        $result = $img->process();

        $this->assertEquals(count($img->calls), 0);
        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals('e795d413cf6598f49a8e773ce2e07589', $result->cachekey);
    }

    public function testGetImageFromCache()
    {
        $lifetime = 12;
        $checksum = '2fff960136929390427f9409eac34c42';
        $imagedata = 'mocked image data';

        $manager = $this->getMockBuilder(ImageManager::class)->getMock();
        $cache = $this->createMock(Repository::class);
        $cache->expects($this->once())
              ->method('get')
              ->with($this->equalTo($checksum))
              ->willReturn($imagedata);

        $img = new ImageCache($manager, $cache);
        $img->make('foo/bar.jpg');
        $img->resize(100, 150);
        $result = $img->get($lifetime);

        $this->assertEquals($imagedata, $result);
    }

    public function testGetImageFromCacheAsObject()
    {
        $lifetime = 12;
        $checksum = '2fff960136929390427f9409eac34c42';
        $imagedata = 'mocked image data';

        $image = $this->getMockBuilder(Image::class)->getMock();
        $manager = $this->getMockBuilder(ImageManager::class)->getMock();
        $manager->expects($this->once())
                ->method('make')
                ->with($this->equalTo($imagedata))
                ->willReturn($image);

        $cache = $this->createMock(Repository::class);
        $cache->expects($this->once())
              ->method('get')
              ->with($this->equalTo($checksum))
              ->willReturn($imagedata);

        $img = new ImageCache($manager, $cache);
        $img->make('foo/bar.jpg');
        $img->resize(100, 150);
        $result = $img->get($lifetime, true);

        $this->assertInstanceOf(Image::class, $result);
    }

    public function testGetImageNotFromCache()
    {
        $lifetime = 12;
        $checksum = '2fff960136929390427f9409eac34c42';
        $imagedata = 'mocked image data';

        $image = $this->getMockBuilder(Image::class)
                      ->setMethods(['resize', 'encode'])
                      ->getMock();

        $image->expects($this->once())
              ->method('resize')
              ->with($this->equalTo(100), $this->equalTo(150))
              ->willReturn($image);

        $image->expects($this->once())
              ->method('encode')
              ->willReturn($imagedata);

        $manager = $this->getMockBuilder(ImageManager::class)->getMock();
        $manager->expects($this->once())
                ->method('make')
                ->with($this->equalTo('foo/bar.jpg'))
                ->willReturn($image);

        $cache = $this->createMock(Repository::class);
        $cache->expects($this->once())
              ->method('get')
              ->with($this->equalTo($checksum))
              ->willReturn(false);

        $cache->expects($this->once())
              ->method('put')
              ->willReturn(false);

        $img = new ImageCache($manager, $cache);
        $img->make('foo/bar.jpg');
        $img->resize(100, 150);
        $result = $img->get($lifetime);

        $this->assertEquals($imagedata, $result);
    }

    public function testGetImageNotFromCacheAsObject()
    {
        $lifetime = 12;
        $checksum = '2fff960136929390427f9409eac34c42';
        $imagedata = 'mocked image data';

        $image = $this->getMockBuilder(Image::class)
                      ->setMethods(['resize', 'encode'])
                      ->getMock();

        $image->expects($this->once())
              ->method('resize')
              ->with($this->equalTo(100), $this->equalTo(150))
              ->willReturn($image);

        $image->expects($this->once())
              ->method('encode')
              ->willReturn($imagedata);

        $manager = $this->getMockBuilder(ImageManager::class)->getMock();
        $manager->expects($this->once())
                ->method('make')
                ->with($this->equalTo('foo/bar.jpg'))
                ->willReturn($image);

        $cache = $this->createMock(Repository::class);
        $cache->expects($this->once())
              ->method('get')
              ->with($this->equalTo($checksum))
              ->willReturn(false);

        $cache->expects($this->once())
              ->method('put')
              ->willReturn(false);

        $img = new ImageCache($manager, $cache);
        $img->make('foo/bar.jpg');
        $img->resize(100, 150);
        $result = $img->get($lifetime, true);

        $this->assertEquals($image, $result);
    }

    public function testOriginalFileChanged()
    {
        $filename = __DIR__ . '/files/foo.bar';

        // create tmp file
        touch($filename);

        // get original checksum
        $img = new ImageCache();
        $img->make($filename);
        $img->resize(300, 200);
        $checksum_original = $img->checksum();

        // get modified checksum
        clearstatcache();
        $modified = touch($filename, 10);

        // get modified checksum
        $img = new ImageCache();
        $img->make($filename);
        $img->resize(300, 200);
        $checksum_modified = $img->checksum();

        // delete tmp file
        unlink($filename);

        $this->assertTrue($modified);
        $this->assertNotEquals($checksum_original, $checksum_modified);
    }

    public function testBinaryInput()
    {
        $data = file_get_contents(__DIR__ . '/files/test.png');
        $img = new ImageCache();
        $result = $img->make($data);
        $this->assertInstanceOf(ImageCache::class, $result);
    }
}

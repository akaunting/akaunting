<?php

namespace Plank\Mediable\Tests\Integration\Helpers;

use Plank\Mediable\Helpers\File;
use Plank\Mediable\Tests\TestCase;

class FileTest extends TestCase
{
    public function test_it_provides_a_cleaned_dirname()
    {
        $this->assertEquals('', File::cleanDirname(''));
        $this->assertEquals('', File::cleanDirname('/'));
        $this->assertEquals('', File::cleanDirname('foo.jpg'));
        $this->assertEquals('foo', File::cleanDirname('/foo/bar/'));
        $this->assertEquals('foo/bar', File::cleanDirname('/foo/bar/baz.jpg'));
    }

    public function test_it_converts_bytes_to_readable_strings()
    {
        $this->assertEquals('0 B', File::readableSize(0));
        $this->assertEquals('1 KB', File::readableSize(1025, 0));
        $this->assertEquals('1.1 MB', File::readableSize(1024 * 1024 + 1024 * 100, 2));
    }

    public function test_it_guesses_the_extension_given_a_mime_type()
    {
        $this->assertEquals('png', File::guessExtension('image/png'));
    }

    public function test_it_sanitizes_filenames()
    {
        $this->assertEquals(
            'hello-world-what-ss_new-with.you',
            File::sanitizeFileName("héllo/world! \\  \t whàt\'ß_new with.you?", 'en')
        );
    }

    public function test_it_sanitizes_filenames_with_locale()
    {
        $this->assertEquals(
            'hello-world-what-sz_new-with.you',
            File::sanitizeFileName("héllo/world! \\  \t whàt\'ß_new with.you?", 'de_at')
        );
    }

    public function test_it_sanitizes_paths()
    {
        $this->assertEquals(
            'hello/world-what-s_new-with.you',
            File::sanitizePath("/héllo/world! \\  \t whàt\'ς_new with.you??")
        );
    }

    public function test_it_joins_path_components()
    {
        $this->assertEquals('', File::joinPathComponents('', ''));
        $this->assertEquals('foo', File::joinPathComponents('foo', ''));
        $this->assertEquals('foo/', File::joinPathComponents('foo/', ''));
        $this->assertEquals('foo/bar', File::joinPathComponents('foo', 'bar'));
        $this->assertEquals('foo/bar', File::joinPathComponents('foo/', 'bar'));
        $this->assertEquals('foo/bar', File::joinPathComponents('foo/', '/bar'));
        $this->assertEquals('foo/bar/baz', File::joinPathComponents('foo', '/bar/', '/baz'));
        $this->assertEquals('foo/baz', File::joinPathComponents('foo', '', 'baz'));
    }
}

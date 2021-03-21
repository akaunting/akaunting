<?php

use Plank\Mediable\Helpers\File;

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
}

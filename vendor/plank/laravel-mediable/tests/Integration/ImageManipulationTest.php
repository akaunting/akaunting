<?php

namespace Plank\Mediable\Tests\Integration;

use Plank\Mediable\ImageManipulation;
use Plank\Mediable\Tests\TestCase;

class ImageManipulationTest extends TestCase
{
    public function test_can_get_set_manipulation_callback()
    {
        $callback = $this->getMockCallable();
        $manipulation = new ImageManipulation($callback);
        $this->assertSame($callback, $manipulation->getCallback());
    }

    public function test_can_get_set_output_quality()
    {
        $manipulation = new ImageManipulation($this->getMockCallable());
        $this->assertEquals(90, $manipulation->getOutputQuality());
        $manipulation->setOutputQuality(-100);
        $this->assertEquals(0, $manipulation->getOutputQuality());
        $manipulation->setOutputQuality(500);
        $this->assertEquals(100, $manipulation->getOutputQuality());
        $manipulation->setOutputQuality(50);
        $this->assertEquals(50, $manipulation->getOutputQuality());
    }

    public function test_can_get_set_output_format()
    {
        $manipulation = new ImageManipulation($this->getMockCallable());
        $this->assertNull($manipulation->getOutputFormat());
        $manipulation->setOutputFormat('jpg');
        $this->assertEquals('jpg', $manipulation->getOutputFormat());
        $manipulation->outputBmpFormat();
        $this->assertEquals('bmp', $manipulation->getOutputFormat());
        $manipulation->outputGifFormat();
        $this->assertEquals('gif', $manipulation->getOutputFormat());
        $manipulation->outputPngFormat();
        $this->assertEquals('png', $manipulation->getOutputFormat());
        $manipulation->outputTiffFormat();
        $this->assertEquals('tif', $manipulation->getOutputFormat());
        $manipulation->outputWebpFormat();
        $this->assertEquals('webp', $manipulation->getOutputFormat());
        $manipulation->outputJpegFormat();
        $this->assertEquals('jpg', $manipulation->getOutputFormat());
    }

    public function test_can_get_set_before_save_callback()
    {
        $callback = $this->getMockCallable();
        $manipulation = new ImageManipulation($this->getMockCallable());

        $this->assertNull($manipulation->getBeforeSave());
        $manipulation->beforeSave($callback);
        $this->assertSame($callback, $manipulation->getBeforeSave());
    }

    public function test_destination_setters()
    {
        $manipulation = new ImageManipulation($this->getMockCallable());

        $this->assertNull($manipulation->getDisk());
        $this->assertNull($manipulation->getDirectory());
        $this->assertNull($manipulation->getFilename());
        $this->assertFalse($manipulation->usingHashForFilename());

        $manipulation->toDisk('tmp');
        $this->assertEquals('tmp', $manipulation->getDisk());

        $manipulation->toDirectory('bar');
        $this->assertEquals('bar', $manipulation->getDirectory());

        $manipulation->toDestination('uploads', 'bat');
        $this->assertEquals('uploads', $manipulation->getDisk());
        $this->assertEquals('bat', $manipulation->getDirectory());

        $manipulation->useFilename('potato');
        $this->assertEquals('potato', $manipulation->getFilename());
        $this->assertFalse($manipulation->usingHashForFilename());

        $manipulation->useHashForFilename();
        $this->assertNull($manipulation->getFilename());
        $this->assertTrue($manipulation->usingHashForFilename());

        $manipulation->useOriginalFilename();
        $this->assertNull($manipulation->getFilename());
        $this->assertFalse($manipulation->usingHashForFilename());
    }

    public function test_get_duplicate_behaviours()
    {
        $manipulation = new ImageManipulation($this->getMockCallable());
        $this->assertEquals(
            ImageManipulation::ON_DUPLICATE_INCREMENT,
            $manipulation->getOnDuplicateBehaviour()
        );
        $manipulation->onDuplicateError();
        $this->assertEquals(
            ImageManipulation::ON_DUPLICATE_ERROR,
            $manipulation->getOnDuplicateBehaviour()
        );
        $manipulation->onDuplicateIncrement();
        $this->assertEquals(
            ImageManipulation::ON_DUPLICATE_INCREMENT,
            $manipulation->getOnDuplicateBehaviour()
        );
    }

    public function test_visibility()
    {
        $manipulation = new ImageManipulation($this->getMockCallable());
        $this->assertNull($manipulation->getVisibility());

        $manipulation->makePublic();
        $this->assertEquals('public', $manipulation->getVisibility());

        $manipulation->makePrivate();
        $this->assertEquals('private', $manipulation->getVisibility());

        $manipulation->matchOriginalVisibility();
        $this->assertEquals('match', $manipulation->getVisibility());

        $manipulation->setVisibility('public');
        $this->assertEquals('public', $manipulation->getVisibility());

        $manipulation->setVisibility('private');
        $this->assertEquals('private', $manipulation->getVisibility());

        $manipulation->setVisibility('match');
        $this->assertEquals('match', $manipulation->getVisibility());

        $manipulation->setVisibility(null);
        $this->assertNull($manipulation->getVisibility());
    }
}

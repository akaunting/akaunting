<?php

namespace Plank\Mediable\Tests\Integration\Jobs;

use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Collection;
use Plank\Mediable\ImageManipulation;
use Plank\Mediable\ImageManipulator;
use Plank\Mediable\Jobs\CreateImageVariants;
use Plank\Mediable\Tests\TestCase;

class CreateImageVariantsTest extends TestCase
{
    public function test_it_will_trigger_image_manipulation()
    {
        $model = $this->makeMedia(['aggregate_type' => 'image']);
        $variant = 'foo';

        $manipulator = $this->createMock(ImageManipulator::class);
        $manipulator->expects($this->once())
            ->method('validateMedia')
            ->with($model);
        $manipulator->expects($this->once())
            ->method('getVariantDefinition')
            ->with($variant)
            ->willReturn($this->createMock(ImageManipulation::class));
        $manipulator->expects($this->once())
            ->method('createImageVariant')
            ->withConsecutive([$model, $variant, false]);
        app()->instance(ImageManipulator::class, $manipulator);

        $job = new CreateImageVariants($model, $variant);
        $job->handle();
    }

    public function test_it_will_trigger_image_manipulation_multiple()
    {
        $model1 = $this->makeMedia(['aggregate_type' => 'image']);
        $model2 = $this->makeMedia(['aggregate_type' => 'image']);
        $variant1 = 'foo';
        $variant2 = 'bar';

        $manipulator = $this->createMock(ImageManipulator::class);
        $manipulator->expects($this->exactly(2))
            ->method('validateMedia')
            ->withConsecutive([$model1], [$model2]);
        $manipulator->expects($this->exactly(2))
            ->method('getVariantDefinition')
            ->withConsecutive([$variant1], [$variant2])
            ->willReturn($this->createMock(ImageManipulation::class));
        $manipulator->expects($this->exactly(4))
            ->method('createImageVariant')
            ->withConsecutive(
                [$model1, $variant1, false],
                [$model1, $variant2, false],
                [$model2, $variant1, false],
                [$model2, $variant2, false]
            );
        app()->instance(ImageManipulator::class, $manipulator);

        $job = new CreateImageVariants(
            new Collection([$model1, $model2]),
            [$variant1, $variant2]
        );
        $job->handle();
    }

    public function test_it_will_trigger_image_manipulation_recreate()
    {
        $model = $this->makeMedia(['aggregate_type' => 'image']);
        $variant1 = 'foo';
        $variant2 = 'bar';

        $manipulator = $this->createMock(ImageManipulator::class);
        $manipulator->expects($this->once())
            ->method('validateMedia')
            ->with($model);
        $manipulator->expects($this->exactly(2))
            ->method('getVariantDefinition')
            ->withConsecutive([$variant1], [$variant2])
            ->willReturn($this->createMock(ImageManipulation::class));
        $manipulator->expects($this->exactly(2))
            ->method('createImageVariant')
            ->withConsecutive(
                [$model, $variant1, true],
                [$model, $variant2, true]
            );
        app()->instance(ImageManipulator::class, $manipulator);

        $job = new CreateImageVariants($model, [$variant1, $variant2], true);
        $job->handle();
    }

    public function test_it_will_serialize_models()
    {
        $this->useDatabase();
        $model = $this->createMedia(['aggregate_type' => 'image']);
        $variant = 'foo';

        $manipulator = $this->createMock(ImageManipulator::class);
        $manipulator->expects($this->once())
            ->method('validateMedia')
            ->with($model);
        $manipulator->expects($this->any())
            ->method('getVariantDefinition')
            ->withConsecutive([$variant])
            ->willReturn($this->createMock(ImageManipulation::class));
        app()->instance(ImageManipulator::class, $manipulator);

        $job = new CreateImageVariants($model, [$variant], true);
        /** @var CreateImageVariants $result */
        $result = unserialize(serialize($job));
        $this->assertEquals([$model->getKey()], $result->getModels()->modelKeys());
        $this->assertEquals([$variant], $result->getVariantNames());
        $this->assertTrue($result->getForceRecreate());
    }
}

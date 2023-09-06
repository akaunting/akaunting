<?php

use Plank\Mediable\Tests\Mocks\MediaSoftDelete;
use Plank\Mediable\Tests\Mocks\SampleMediable;
use Plank\Mediable\Tests\Mocks\SampleMediableSoftDelete;

$factory->define(Plank\Mediable\Media::class, function (Faker\Generator $faker) {
    $types = config('mediable.aggregate_types');
    $type = $faker->randomElement(array_keys($types));

    return [
        'disk' => 'tmp',
        'directory' => implode('/', $faker->words($faker->randomDigit)),
        'filename' => $faker->word,
        'extension' => $faker->randomElement($types[$type]['extensions']),
        'mime_type' => $faker->randomElement($types[$type]['mime_types']),
        'aggregate_type' => $type,
        'size' => $faker->randomNumber(),
    ];
});

$factory->define(MediaSoftDelete::class, function (Faker\Generator $faker) {
    $types = config('mediable.aggregate_types');
    $type = $faker->randomElement(array_keys($types));

    return [
        'disk' => 'tmp',
        'directory' => implode('/', $faker->words($faker->randomDigit)),
        'filename' => $faker->word,
        'extension' => $faker->randomElement($types[$type]['extensions']),
        'mime_type' => $faker->randomElement($types[$type]['mime_types']),
        'aggregate_type' => $type,
        'size' => $faker->randomNumber(),
    ];
});

$factory->define(SampleMediable::class, function (Faker\Generator $faker) {
    return [];
});

$factory->define(SampleMediableSoftDelete::class, function (Faker\Generator $faker) {
    return [];
});

<?php

namespace Tests\Feature\Common;

use Tests\Feature\FeatureTestCase;
use App\Jobs\Setting\CreateCategory;

class SourcesTest extends FeatureTestCase
{
    public function testItShouldHaveAutoSource()
    {
        $request = $this->getRequest();

        $category = $this->dispatch(new CreateCategory($request));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'created_from' => 'core::console',
        ]);
    }

    public function testItShouldHaveManualSource()
    {
        $request = $this->getRequest();

        $request['created_from'] = 'manual';

        $category = $this->dispatch(new CreateCategory($request));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'created_from' => 'manual',
        ]);
    }

    public function getRequest()
    {
        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'type' => 'income',
            'color' => $this->faker->hexColor,
            'enabled' => $this->faker->boolean ? 1 : 0,
        ];
    }
}

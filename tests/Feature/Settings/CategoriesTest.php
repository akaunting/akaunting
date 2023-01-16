<?php

namespace Tests\Feature\Settings;

use App\Jobs\Setting\CreateCategory;
use App\Models\Setting\Category;
use Tests\Feature\FeatureTestCase;

class CategoriesTest extends FeatureTestCase
{
    public function testItShouldSeeCategoryListPage()
    {
        $this->loginAs()
            ->get(route('categories.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.categories', 2));
    }

    public function testItShouldSeeCategoryCreatePage()
    {
        $this->loginAs()
            ->get(route('categories.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.categories', 1)]));
    }

    public function testItShouldCreateCategory()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('categories.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('categories', $request);
    }

    public function testItShouldSeeCategoryUpdatePage()
    {
        $request = $this->getRequest();

        $category = $this->dispatch(new CreateCategory($request));

        $this->loginAs()
            ->get(route('categories.edit', $category->id))
            ->assertStatus(200)
            ->assertSee($category->name);
    }

    public function testItShouldUpdateCategory()
    {
        $request = $this->getRequest();

        $category = $this->dispatch(new CreateCategory($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('categories.update', $category->id), $request)
            ->assertStatus(200)
			->assertSee($request['name']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('categories', $request);
    }

    public function testItShouldDeleteCategory()
    {
        $request = $this->getRequest();

        $category = $this->dispatch(new CreateCategory($request));

        $this->loginAs()
            ->delete(route('categories.destroy', $category->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('categories', $request);
    }

    public function getRequest()
    {
        return Category::factory()->enabled()->raw();
    }
}

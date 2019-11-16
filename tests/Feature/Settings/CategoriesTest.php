<?php

namespace Tests\Feature\Settings;

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
        $this->loginAs()
            ->post(route('categories.store'), $this->getCategoryRequest())
            ->assertStatus(302)
            ->assertRedirect(route('categories.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeCategoryUpdatePage()
    {
        $category = Category::create($this->getCategoryRequest());

        $this->loginAs()
            ->get(route('categories.edit', ['category' => $category->id]))
            ->assertStatus(200)
            ->assertSee($category->name);
    }

    public function testItShouldUpdateCategory()
    {
        $request = $this->getCategoryRequest();

        $category = Category::create($request);

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('categories.update', $category->id), $request)
            ->assertStatus(302)
            ->assertRedirect(route('categories.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteCategory()
    {
        $category = Category::create($this->getCategoryRequest());

        $this->loginAs()
            ->delete(route('categories.destroy', $category->id))
            ->assertStatus(302)
            ->assertRedirect(route('categories.index'));

        $this->assertFlashLevel('success');
    }

    private function getCategoryRequest()
    {
        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'type' => 'other',
            'color' => $this->faker->text(15),
            'enabled' => $this->faker->boolean ? 1 : 0
        ];
    }
}

<?php
namespace Tests\Feature\Wizard;

use App\Jobs\Setting\CreateTax;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

class TaxesTest extends FeatureTestCase
{
    public function testItShouldSeeTaxListPage()
    {
        $this->loginAs()
            ->get(route('wizard.taxes.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.add_new'));
    }

    public function testItShouldCreateTax()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('wizard.taxes.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('taxes', $request);
    }

    public function testItShouldUpdateTax()
    {
        $request = $this->getRequest();

        $tax = $this->dispatch(new CreateTax($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('wizard.taxes.update', $tax->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('taxes', $request);
    }

    public function testItShouldDeleteTax()
    {
        $request = $this->getRequest();

        $tax = $this->dispatch(new CreateTax($request));

        $this->loginAs()
            ->delete(route('wizard.taxes.destroy', $tax->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('taxes', $request);
    }

    public function getRequest()
    {
        return Tax::factory()->enabled()->raw();
    }
}

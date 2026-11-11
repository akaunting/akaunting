<?php

namespace Tests\Feature\Settings;

use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Setting\CreateTax;
use App\Models\Banking\Transaction;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

class TaxesTest extends FeatureTestCase
{
    public function testItShouldSeeTaxListPage()
    {
        $this->loginAs()
            ->get(route('taxes.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.tax_rates', 2));
    }

    public function testItShouldSeeTaxCreatePage()
    {
        $this->loginAs()
            ->get(route('taxes.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.taxes', 1)]));
    }

    public function testItShouldCreateTax()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('taxes.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('taxes', $request);
    }

    public function testItShouldSeeTaxUpdatePage()
    {
        $request = $this->getRequest();

        $tax = $this->dispatch(new CreateTax($request));

        $this->loginAs()
            ->get(route('taxes.edit', $tax->id))
            ->assertStatus(200)
            ->assertSee($tax->name);
    }

    public function testItShouldUpdateTax()
    {
        $request = $this->getRequest();

        $tax = $this->dispatch(new CreateTax($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('taxes.update', $tax->id), $request)
            ->assertStatus(200)
            ->assertSee($request['name']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('taxes', $request);
    }

    public function testItShouldDeleteTax()
    {
        $request = $this->getRequest();

        $tax = $this->dispatch(new CreateTax($request));

        $this->loginAs()
            ->delete(route('taxes.destroy', $tax->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('taxes', $request);
    }

    public function testItShouldNotDeleteTaxUsedOnTransaction()
    {
        $tax = $this->createTaxUsedOnTransaction();

        $this->loginAs()
            ->delete(route('taxes.destroy', $tax->id))
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertNotSoftDeleted($tax);
    }

    public function testItShouldNotDisableTaxUsedOnTransaction()
    {
        $tax = $this->createTaxUsedOnTransaction();

        $this->loginAs()
            ->get(route('taxes.disable', $tax->id))
            ->assertStatus(200)
            ->assertJson(['success' => false]);

        $this->assertTrue($tax->fresh()->enabled);
    }

    public function testItShouldNotChangeTypeOfTaxUsedOnTransaction()
    {
        $tax = $this->createTaxUsedOnTransaction();

        $request = [
            'name' => $tax->name,
            'rate' => $tax->rate,
            'type' => 'inclusive',
            'enabled' => 1,
        ];

        $this->loginAs()
            ->patch(route('taxes.update', $tax->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertEquals('normal', $tax->fresh()->type);
    }

    public function testItShouldDeleteTaxOfDeletedTransaction()
    {
        $tax = $this->createTaxUsedOnTransaction();

        $this->dispatch(new DeleteTransaction($tax->transactions()->first()->transaction));

        $this->loginAs()
            ->delete(route('taxes.destroy', $tax->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted($tax);
    }

    public function createTaxUsedOnTransaction(): Tax
    {
        $tax = $this->dispatch(new CreateTax(Tax::factory()->normal()->enabled()->raw()));

        $request = Transaction::factory()->income()->raw();
        $request['tax_ids'] = [$tax->id];

        $this->dispatch(new CreateTransaction($request));

        return $tax;
    }

    public function getRequest()
    {
        return Tax::factory()->enabled()->raw();
    }
}

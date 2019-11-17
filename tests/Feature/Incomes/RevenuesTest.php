<?php

namespace Tests\Feature\Incomes;

use App\Jobs\Banking\CreateTransaction;
use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class RevenuesTest extends FeatureTestCase
{
    public function testItShouldSeeRevenueListPage()
    {
        $this->loginAs()
            ->get(route('revenues.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.revenues', 2));
    }

    public function testItShouldSeeRevenueCreatePage()
    {
        $this->loginAs()
            ->get(route('revenues.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.revenues', 1)]));
    }

   public function testItShouldCreateRevenue()
    {
        $this->loginAs()
            ->post(route('revenues.store'), $this->getRevenueRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdateRevenue()
    {
        $request = $this->getRevenueRequest();

        $revenue = $this->dispatch(new CreateTransaction($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('revenues.update', $revenue->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteRevenue()
    {
        $revenue = $this->dispatch(new CreateTransaction($this->getRevenueRequest()));

        $this->loginAs()
            ->delete(route('revenues.destroy', $revenue->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getRevenueRequest()
    {
        $attachment = UploadedFile::fake()->create('image.jpg');

        return [
            'company_id' => $this->company->id,
            'type' => 'income',
            'account_id' => setting('default.account'),
            'paid_at' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 2),
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1',
            'description' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('income')->first()->id,
            'reference' => $this->faker->text(5),
            'payment_method' => setting('default.payment_method'),
            'attachment' => $attachment,
        ];
    }
}

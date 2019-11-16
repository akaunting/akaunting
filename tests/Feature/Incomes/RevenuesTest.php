<?php

namespace Tests\Feature\Incomes;

use App\Models\Income\Revenue;
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
            ->assertStatus(302)
            ->assertRedirect(route('revenues.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdateRevenue()
    {
        $request = $this->getRevenueRequest();

        $revenue = Revenue::create($request);

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('revenues.update', $revenue->id), $request)
            ->assertStatus(302)
            ->assertRedirect(route('revenues.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteRevenue()
    {
        $revenue = Revenue::create($this->getRevenueRequest());

        $this->loginAs()
            ->delete(route('revenues.destroy', $revenue->id))
            ->assertStatus(302)
            ->assertRedirect(route('revenues.index'));

        $this->assertFlashLevel('success');
    }

    private function getRevenueRequest()
    {
        $attachment = UploadedFile::fake()->create('image.jpg');

        return [
            'company_id' => $this->company->id,
            'customer_id' => '',
            'account_id' => setting('general.default_account'),
            'paid_at' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 2),
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => '1',
            'description' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('income')->first()->id,
            'reference' => $this->faker->text(5),
            'payment_method' => setting('general.default_payment_method'),
            'attachment' => $attachment,
        ];
    }
}

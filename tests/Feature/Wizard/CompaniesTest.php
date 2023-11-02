<?php

namespace Tests\Feature\Wizard;

use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class CompaniesTest extends FeatureTestCase
{
    public function testItShouldSeeCompanyUpdatePage()
    {
        $this->loginAs()
            ->get(route('wizard.companies.edit'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.wizard'));
    }

    public function testItShouldUpdateCompany()
    {
        $request = $this->getRequest();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.companies', 2)]);

        $this->loginAs()
            ->post(route('wizard.companies.update'), $request)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => $message,
            ]);
    }

    public function getRequest()
    {
        return [
            'financial_start' => '01-04',
            'address' => $this->faker->address,
            'tax_number' => $this->faker->randomNumber(9),
            'logo' => UploadedFile::fake()->image('akaunting-logo.jpg'),
        ];
    }
}

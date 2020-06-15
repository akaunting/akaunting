<?php

namespace Tests\Feature\Settings;

use Illuminate\Http\UploadedFile;
use Tests\Feature\FeatureTestCase;

class CompaniesTest extends FeatureTestCase
{
    public function testItShouldSeeCompanyUpdatePage()
    {
        $this->loginAs()
            ->get(route('settings.company.edit'))
            ->assertStatus(200);
    }

    public function testItShouldUpdateCompany()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->patch(route('settings.update'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }
    public function getRequest()
    {
        return [
            'financial_start' => '01-04',
            'address' => $this->faker->address,
            'tax_number' => $this->faker->randomNumber(9),
            'phone' => $this->faker->phoneNumber,
            'email'=>$this->faker->safeEmail,
            'logo' => UploadedFile::fake()->create('image.jpg'),
        ];
    }
}

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
            ->assertSeeText(trans('modules.api_key'));
    }

    public function testItShouldUpdateCompany()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->patch(route('wizard.companies.update'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return [
            'financial_start' => '01-04',
            'address' => $this->faker->address,
            'tax_number' => $this->faker->randomNumber(9),
            'logo' => UploadedFile::fake()->create('image.jpg'),
        ];
    }
}

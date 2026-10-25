<?php

namespace Tests\Feature\Wizard;

use App\Models\Common\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\Feature\FeatureTestCase;
use Image;

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

    public function testItShouldStoreCroppedCompanyLogo()
    {
        $request = $this->getRequest();
        $request['logo'] = UploadedFile::fake()->image('akaunting-logo.jpg', 1000, 1000);

        $this->loginAs()
            ->post(route('wizard.companies.update'), $request)
            ->assertOk();

        $media = Media::find(setting('company.logo'));
        $this->assertNotNull($media);

        $image = Image::make($media->contents());

        $this->assertLessThanOrEqual(config('filesystems.max_width'), $image->width());
        $this->assertLessThanOrEqual(config('filesystems.max_height'), $image->height());
        $this->assertSame($image->width(), $image->height());
    }

    public function testItShouldRejectOversizedCompanyLogo()
    {
        $request = $this->getRequest();
        $request['logo'] = UploadedFile::fake()->image('akaunting-logo.jpg', 2000, 1000);

        $this->expectException(ValidationException::class);

        $this->loginAs()
            ->post(route('wizard.companies.update'), $request);
    }

    public function getRequest()
    {
        return [
            'financial_start' => '01-04',
            'address' => $this->faker->address,
            'tax_number' => $this->faker->randomNumber(9),
            'country' => $this->faker->randomElement(array_keys(trans('countries'))),
            //'logo' => UploadedFile::fake()->image('akaunting-logo.jpg'),
        ];
    }
}

<?php

namespace Tests\Feature\Common;

use App\Models\Common\Company;
use Tests\Feature\FeatureTestCase;

class CompaniesTest extends FeatureTestCase
{
    public function testItShouldSeeCompanyInDashboard()
    {
        $this->loginAs()
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText($this->company->name)
            ->assertSeeText(trans('general.title.manage', ['type' => trans_choice('general.companies', 2)]));
    }

    public function testItShouldSeeCompanyListPage()
    {
        $this->loginAs()
            ->get(route('companies.index'))
            ->assertOk()
            ->assertSeeText(trans_choice('general.companies', 2));
    }

    public function testItShouldSeeCompanyCreatePage()
    {
        $this->loginAs()
            ->get(route('companies.create'))
            ->assertOk()
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.companies', 1)]));
    }

    public function testItShouldCreateCompany()
    {
        $request = $this->getRequest();

        $response = $this->loginAs()
                        ->post(route('companies.store'), $request)
                        ->assertOk();

        $this->assertFlashLevel('success');

        $this->assertHasCompany($response->baseResponse->original['data']->id, $request);
    }

    public function testItShouldSwitchCompany()
    {
        $request = $this->getRequest();

        $company = $this->createCompany($request);

        $this->loginAs()
            ->get(route('companies.switch', $company->id))
            ->assertStatus(302);

        $this->assertEquals($company->id, company_id());
    }

    public function testItShouldSeeCompanyUpdatePage()
    {
        $request = $this->getRequest();

        $company = $this->createCompany($request);

        $this->loginAs()
            ->get(route('companies.edit', $company->id))
            ->assertOk()
            ->assertSee($company->name);
    }

    public function testItShouldUpdateCompany()
    {
        $request = $this->getRequest();

        $company = $this->createCompany($request);

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('companies.update', $company->id), $request)
            ->assertOk();

        $this->assertFlashLevel('success');

        $this->assertHasCompany($company->id, $request);
    }

    public function testItShouldDeleteCompany()
    {
        $request = $this->getRequest();

        $company = $this->createCompany($request);

        $this->loginAs()
            ->delete(route('companies.destroy', $company->id))
            ->assertOk();

        $this->assertFlashLevel('success');
    }

    public function getRequest(): array
    {
        return Company::factory()->enabled()->raw();
    }

    public function createCompany(array $request): Company
    {
        $response = $this->loginAs()
                        ->post(route('companies.store'), $request)
                        ->assertOk();

        return $response->baseResponse->original['data'];
    }

    public function assertHasCompany(int $id, array $request): void
    {
        company($id)->makeCurrent();

        $this->assertEquals(setting('company.name'), $request['name']);
        $this->assertEquals(setting('company.email'), $request['email']);
        $this->assertEquals(setting('company.country'), $request['country']);
        $this->assertEquals(setting('default.currency'), $request['currency']);
    }
}

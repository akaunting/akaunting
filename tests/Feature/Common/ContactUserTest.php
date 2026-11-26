<?php

namespace Tests\Feature\Common;

use App\Imports\Sales\Customers\Sheets\Customers as CustomersSheet;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\CreateContact;
use App\Models\Auth\Permission;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use Tests\Feature\FeatureTestCase;

class ContactUserTest extends FeatureTestCase
{
    public function testItShouldNotLinkAContactToAUserSentInTheRequest()
    {
        $victim = $this->createUserOfAnotherCompany();
        $customer = $this->dispatch(new CreateContact($this->getRequest()));

        // The user first, then a password for it, as the contact would pass them on
        $this->loginAs()
            ->patch(route('customers.update', $customer->id), array_merge($this->getRequest(), ['user_id' => $victim->id]))
            ->assertOk();

        $this->assertNull($customer->fresh()->user_id);

        $this->loginAs()
            ->patch(route('customers.update', $customer->id), array_merge($this->getRequest(), ['password' => 'taken-over']))
            ->assertOk();

        $this->assertSame($victim->getAuthPassword(), $victim->fresh()->getAuthPassword());

        // Nor over the API
        $this->withHeaders(['Authorization' => 'Basic ' . base64_encode('test@company.com:123456')])
            ->patchJson(route('api.contacts.update', [$customer->id, 'search' => 'type:customer']), array_merge($this->getRequest(), ['user_id' => $victim->id]))
            ->assertOk();

        $this->assertNull($customer->fresh()->user_id);
    }

    public function testItShouldOnlyChangeTheNameAndEmailOfTheUserOfAContact()
    {
        $request = $this->getRequestWithUser();

        $this->loginAs()->post(route('customers.store'), $request)->assertOk();

        $customer = Contact::where('email', $request['email'])->first();
        $user = $customer->user;
        $password = $user->getAuthPassword();

        $this->loginAs()
            ->patch(route('customers.update', $customer->id), array_merge($this->getRequest(), [
                'name' => 'Renamed',
                'email' => $email = $this->faker->freeEmail,
                'password' => 'taken-over',
                'enabled' => 1,
            ]))
            ->assertOk();

        $user = $user->fresh();

        $this->assertSame('Renamed', $user->name);
        $this->assertSame($email, $user->email);
        $this->assertSame($password, $user->getAuthPassword());
    }

    public function testItShouldKeepTheUserOfAContactThatIsInOtherCompaniesToo()
    {
        $victim = $this->createUserOfAnotherCompany();
        $customer = $this->dispatch(new CreateContact($this->getRequest()));

        // A link made before, by the request or the import
        $customer->forceFill(['user_id' => $victim->id])->saveQuietly();

        $this->loginAs()
            ->patch(route('customers.update', $customer->id), array_merge($this->getRequest(), [
                'name' => 'Renamed',
                'email' => $this->faker->freeEmail,
            ]))
            ->assertOk();

        $fresh = $victim->fresh();

        $this->assertSame($victim->name, $fresh->name);
        $this->assertSame($victim->email, $fresh->email);
    }

    public function testItShouldNotGiveTheUserOfAContactPermissions()
    {
        $permission = Permission::where('name', 'read-admin-panel')->first();

        $request = $this->getRequestWithUser() + ['permissions' => [$permission->id]];

        $this->loginAs()->post(route('customers.store'), $request)->assertOk();

        $user = user_model_class()::where('email', $request['email'])->first();

        $this->assertNotNull($user);
        $this->assertCount(0, $user->permissions);
        $this->assertFalse($user->can('read-admin-panel'));
    }

    public function testItShouldLinkAnImportedCustomerToACustomerUserOfTheCompanyOnly()
    {
        $this->loginAs();

        $sheet = new CustomersSheet();

        // The admin of the company and a user of another company are not customers to link
        foreach ([$this->user, $this->createUserOfAnotherCompany()] as $user) {
            $row = $sheet->map(['name' => 'Imported', 'email' => $user->email, 'can_login' => 'true', 'currency_code' => 'USD']);

            $this->assertNull($row['user_id']);
        }

        // A customer user of this company is
        $request = $this->getRequestWithUser();

        $this->loginAs()->post(route('customers.store'), $request)->assertOk();

        $customer_user = user_model_class()::where('email', $request['email'])->first();

        $this->loginAs();

        $row = $sheet->map(['name' => 'Imported', 'email' => $customer_user->email, 'can_login' => 'true', 'currency_code' => 'USD']);

        $this->assertSame($customer_user->id, $row['user_id']);
    }

    /**
     * The owner of a company the logged in user is not in.
     */
    protected function createUserOfAnotherCompany()
    {
        $owner = $this->dispatch(new CreateUser(array_merge(user_model_class()::factory()->raw(), ['enabled' => 1])));

        // CreateCompany assigns the new company to the user creating it
        $this->actingAs($owner);

        $this->dispatch(new CreateCompany(Company::factory()->enabled()->raw([
            'settings' => ['wizard.completed' => '1', 'email.protocol' => 'array'],
        ])));

        $owner->companies()->detach($this->company->id);

        $this->company->makeCurrent();

        return $owner->fresh();
    }

    protected function getRequest(): array
    {
        return Contact::factory()->customer()->enabled()->raw();
    }

    protected function getRequestWithUser(): array
    {
        return $this->getRequest() + [
            'create_user' => 'true',
            'locale' => 'en-GB',
            'password' => 'user-password',
            'password_confirmation' => 'user-password',
        ];
    }
}

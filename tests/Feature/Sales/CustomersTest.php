<?php

namespace Tests\Feature\Sales;

use App\Exports\Sales\Customers as Export;
use App\Jobs\Common\CreateContact;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\FeatureTestCase;

class CustomersTest extends FeatureTestCase
{
    public function testItShouldSeeCustomerListPage()
    {
        $this->loginAs()
            ->get(route('customers.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.customers', 2));
    }

    public function testItShouldSeeCustomerShowPage()
    {
        $request = $this->getRequest();

        $customer = $this->dispatch(new CreateContact($request));

        $this->loginAs()
            ->get(route('customers.show', $customer->id))
            ->assertStatus(200)
            ->assertSee($customer->email);
    }

    public function testItShouldSeeCustomerCreatePage()
    {
        $this->loginAs()
            ->get(route('customers.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.customers', 1)]));
    }

    public function testItShouldCreateCustomer()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('customers.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('contacts', $request);
    }

    public function testItShouldCreateCustomerWithUser()
    {
        $request = $this->getRequestWithUser();

        $this->loginAs()
            ->post(route('customers.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $user = User::where('email', $request['email'])->first();

        $this->assertNotNull($user);
        $this->assertEquals($request['email'], $user->email);
    }

    public function testItShouldSeeCustomerDetailPage()
    {
        $request = $this->getRequest();

        $customer = $this->dispatch(new CreateContact($request));

        $this->loginAs()
            ->get(route('customers.show', $customer->id))
            ->assertStatus(200)
            ->assertSee($customer->email);
    }

    public function testItShouldSeeCustomerUpdatePage()
    {
        $request = $this->getRequest();

        $customer = $this->dispatch(new CreateContact($request));

        $this->loginAs()
            ->get(route('customers.edit', $customer->id))
            ->assertStatus(200)
            ->assertSee($customer->email);
    }

    public function testItShouldUpdateCustomer()
    {
        $request = $this->getRequest();

        $customer = $this->dispatch(new CreateContact($request));

        $request['email'] = $this->faker->freeEmail;

        $this->loginAs()
            ->patch(route('customers.update', $customer->id), $request)
            ->assertStatus(200)
            ->assertSee($request['email']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('contacts', $request);
    }

    public function testItShouldDeleteCustomer()
    {
        $request = $this->getRequest();

        $customer = $this->dispatch(new CreateContact($request));

        $this->loginAs()
            ->delete(route('customers.destroy', $customer->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('contacts', $request);
    }

    public function testItShouldNotDeleteCustomerIfHasRelations()
    {
        $this->assertTrue(true);
        //TODO : This will write after done invoice and revenues tests.
    }

    public function testItShouldExportCustomers()
    {
        Contact::factory()->customer()->count(5)->create();
        $count = Contact::customer()->count();

        Excel::fake();

        $this->loginAs()
            ->get(route('customers.export'))
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.customers', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedCustomers()
    {
        $create_count = 5;
        $select_count = 3;

        $customers = Contact::factory()->customer()->count($create_count)->create();

        Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'sales', 'type' => 'customers']),
                ['handle' => 'export', 'selected' => $customers->take($select_count)->pluck('id')->toArray()]
            )
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.customers', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($select_count) {
                return $export->collection()->count() === $select_count;
            }
        );
    }

    public function testItShouldImportCustomers()
    {
        Excel::fake();

        $this->loginAs()
            ->post(
                route('customers.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'customers.xlsx',
                        File::get(public_path('files/import/customers.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        Excel::assertImported('customers.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return Contact::factory()->customer()->enabled()->raw();
    }

    public function getRequestWithUser()
    {
        $password = $this->faker->password;

        return $this->getRequest() + [
            'create_user' => 'true',
            'locale' => 'en-GB',
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }
}

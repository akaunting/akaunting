<?php

namespace Tests\Feature\Sales;

use App\Exports\Sales\Customers\Customers as Export;
use App\Jobs\Common\CreateContact;
use App\Jobs\Document\CreateDocument;
use App\Models\Common\Contact;
use App\Models\Document\Document;
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

    public function testItShouldNotCreateCustomerWithInvalidUser()
    {
        $request = $this->getRequest();
        $request['user_id'] = 99999;

        $this->withExceptionHandling()
            ->loginAs()
            ->postJson(route('customers.store'), $request)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }

    public function testItShouldCreateCustomerWithUser()
    {
        $request = $this->getRequestWithUser();

        $this->loginAs()
            ->post(route('customers.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $user = user_model_class()::where('email', $request['email'])->first();

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

    public function testItShouldKeepContactPersonsAndLogoOfCustomerWhenDisablingAndEnabling()
    {
        $customer = $this->createCustomerWithContactPersonAndLogo();

        $this->loginAs()
            ->get(route('customers.disable', $customer->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(0, $customer->fresh()->enabled);
        $this->assertCount(1, $customer->contact_persons()->get());
        $this->assertTrue($customer->fresh()->hasMedia('logo'));

        $this->loginAs()
            ->get(route('customers.enable', $customer->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(1, $customer->fresh()->enabled);
        $this->assertCount(1, $customer->contact_persons()->get());
        $this->assertTrue($customer->fresh()->hasMedia('logo'));
    }

    public function testItShouldKeepContactPersonsAndRecurringInvoicesOfCustomerInBulkEdit()
    {
        $customer = $this->createCustomerWithContactPersonAndLogo();

        $request = Document::factory()->invoice()->items()->recurring()->raw();
        $request['contact_id'] = $customer->id;
        $request['contact_name'] = $customer->name;

        $recurring_invoice = $this->dispatch(new CreateDocument($request));

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'sales', 'type' => 'customers']),
                ['handle' => 'update', 'selected' => [$customer->id], 'country' => 'TR']
            )
            ->assertStatus(200);

        $this->assertEquals('TR', $customer->fresh()->country);
        $this->assertCount(1, $customer->contact_persons()->get());
        $this->assertTrue($customer->fresh()->hasMedia('logo'));

        $recurring_invoice = Document::invoiceRecurring()->find($recurring_invoice->id);

        $this->assertEquals($customer->name, $recurring_invoice->contact_name);
        $this->assertEquals('TR', $recurring_invoice->contact_country);
    }

    public function createCustomerWithContactPersonAndLogo(): Contact
    {
        $request = $this->getRequest();
        $request['contact_persons'] = [
            ['name' => $this->faker->name, 'email' => $this->faker->freeEmail, 'phone' => $this->faker->phoneNumber],
        ];
        $request['logo'] = UploadedFile::fake()->image('logo.png', 100, 100);

        $this->loginAs()
            ->post(route('customers.store'), $request)
            ->assertStatus(200);

        $customer = Contact::where('email', $request['email'])->first();

        $this->assertCount(1, $customer->contact_persons()->get());
        $this->assertTrue($customer->hasMedia('logo'));

        return $customer;
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
                return $export->sheets()[0]->collection()->count() === $count;
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
                return $export->sheets()[0]->collection()->count() === $select_count;
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

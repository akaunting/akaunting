<?php

namespace Tests\Feature\Sales;

use App\Jobs\Common\CreateContact;
use App\Models\Auth\User;
use App\Models\Common\Contact;
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

	public function testItShouldSeeCustomerCreatePage()
	{
		$this->loginAs()
			->get(route('customers.create'))
			->assertStatus(200)
			->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.customers', 1)]));
	}

	public function testItShouldCreateOnlyCustomerWithoutUser()
	{
		$this->loginAs()
			->post(route('customers.store'), $this->getRequest())
			->assertStatus(200);

		$this->assertFlashLevel('success');
	}

	public function testItShouldCreateCustomerWithUser()
	{
        $customer = $this->getRequestWithUser();

		$this->loginAs()
			->post(route('customers.store'), $customer)
			->assertStatus(200);

		$this->assertFlashLevel('success');

		$user = User::where('email', $customer['email'])->first();

		$this->assertNotNull($user);
		$this->assertEquals($customer['email'], $user->email);
	}

	public function testItShouldSeeCustomerDetailPage()
	{
		$customer = $this->dispatch(new CreateContact($this->getRequest()));

		$this->loginAs()
			->get(route('customers.show', $customer->id))
			->assertStatus(200)
			->assertSee($customer->email);
	}

	public function testItShouldSeeCustomerUpdatePage()
	{
		$customer = $this->dispatch(new CreateContact($this->getRequest()));

		$this->loginAs()
			->get(route('customers.edit', $customer->id))
			->assertStatus(200)
			->assertSee($customer->email);
	}

	public function testItShouldUpdateCustomer()
	{
		$request = $this->getRequest();

		$customer = $this->dispatch(new CreateContact($request));

        $request['email'] = $this->faker->safeEmail;

		$this->loginAs()
			->patch(route('customers.update', $customer->id), $request)
			->assertStatus(200)
			->assertSee($request['email']);

		$this->assertFlashLevel('success');
	}

	public function testItShouldDeleteCustomer()
	{
		$customer = $this->dispatch(new CreateContact($this->getRequest()));

		$this->loginAs()
			->delete(route('customers.destroy', $customer->id))
			->assertStatus(200);

		$this->assertFlashLevel('success');

	}

	public function testItShouldNotDeleteCustomerIfHasRelations()
	{
		$this->assertTrue(true);
		//TODO : This will write after done invoice and revenues tests.
	}

    public function getRequest()
    {
        return factory(Contact::class)->states('customer', 'enabled')->raw();
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

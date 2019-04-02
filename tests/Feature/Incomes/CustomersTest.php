<?php

namespace Tests\Feature\Incomes;

use App\Models\Auth\User;
use App\Models\Income\Customer;
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
			->post(route('customers.store'), $this->getCustomerRequest())
			->assertStatus(302)
			->assertRedirect(route('customers.index'));

		$this->assertFlashLevel('success');
	}

	public function testItShouldCreateCustomerWithUser()
	{
        $customer = $this->getCustomerRequestWithUser();

		$this->loginAs()
			->post(route('customers.store'), $customer)
			->assertStatus(302)
			->assertRedirect(route('customers.index'));

		$this->assertFlashLevel('success');

		$user = User::where('email', $customer['email'])->first();
		
		$this->assertNotNull($user);
		$this->assertEquals($customer['email'], $user->email);
	}

	public function testItShouldNotCreateCustomerWithExistsUser()
	{
        $customer = $this->getCustomerRequestWithUser();

		User::create($customer);

		$this->loginAs()
			->post(route('customers.store'), $customer)
			->assertSessionHasErrors(['email']);
	}

	public function testItShouldSeeCustomerDetailPage()
	{
		$customer = Customer::create($this->getCustomerRequest());

		$this->loginAs()
			->get(route('customers.show', ['customer' => $customer->id]))
			->assertStatus(200)
			->assertSee($customer->email);
	}

	public function testItShouldSeeCustomerUpdatePage()
	{
		$customer = Customer::create($this->getCustomerRequest());

		$this->loginAs()
			->get(route('customers.edit', ['customer' => $customer->id]))
			->assertStatus(200)
			->assertSee($customer->email)
			->assertSee($customer->name);
	}

	public function testItShouldUpdateCustomer()
	{
		$request = $this->getCustomerRequest();

		$customer = Customer::create($request);

        $request['name'] = $this->faker->name;

		$this->loginAs()
			->patch(route('customers.update', $customer->id), $request)
			->assertStatus(302)
			->assertRedirect(route('customers.index'));

		$this->assertFlashLevel('success');
	}

	public function testItShouldDeleteCustomer()
	{
		$customer = Customer::create($this->getCustomerRequest());

		$this->loginAs()
			->delete(route('customers.destroy', $customer->id))
			->assertStatus(302)
			->assertRedirect(route('customers.index'));
		
		$this->assertFlashLevel('success');

	}

	public function testItShouldNotDeleteCustomerIfHasRelations()
	{
		$this->assertTrue(true);
		//TODO : This will write after done invoice and revenues tests.
	}

	private function getCustomerRequest()
	{
		return [
			'company_id' => $this->company->id,
			'name' => $this->faker->name,
			'email' => $this->faker->email,
			'tax_number' => $this->faker->randomNumber(9),
			'phone' => $this->faker->phoneNumber,
			'address' => $this->faker->address,
			'website' => 'www.akaunting.com',
			'currency_code' => $this->company->currencies()->enabled()->first()->code,
			'enabled' => $this->faker->boolean ? 1 : 0
		];
	}

	private function getCustomerRequestWithUser()
	{
		$password = $this->faker->password;

		return $this->getCustomerRequest() + [
				'create_user' => 1,
				'locale' => 'en-GB',
				'password' => $password,
				'password_confirmation' => $password
			];
	}
}

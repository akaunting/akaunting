<?php

namespace Tests\Feature\Incomes;

use App\Models\Auth\User;
use App\Models\Income\Customer;
use Tests\Feature\FeatureTestCase;

class CustomersTest extends FeatureTestCase
{
	public function testItShouldCreateOnlyCustomerWithoutUser()
	{
		$customer = $this->getCustomerData();
		$this->loginAs()
			->post(route("customers.store"), $customer)
			->assertStatus(302)
			->assertRedirect(route("customers.index"));
		$this->assertFlashLevel("success");
	}

	public function testItShouldCreateCustomerWithUser()
	{
		$customerWithUser = $this->getCustomerDataWithUser();

		$this->loginAs()
			->post(route("customers.store"), $customerWithUser)
			->assertStatus(302)
			->assertRedirect(route("customers.index"));
		$this->assertFlashLevel("success");

		$user = User::where("email", $customerWithUser["email"])->first();
		$this->assertNotNull($user);
		$this->assertEquals($customerWithUser["email"], $user->email);
	}

	public function testItShouldNotCreateCustomerWithExistsUser()
	{
		$customerWithUser = $this->getCustomerDataWithUser();
		User::create($customerWithUser);

		$this->loginAs()
			->post(route('customers.store'), $customerWithUser)
			->assertSessionHasErrors(['email']);
	}

	public function testItShouldBeSeeTheCustomersPage()
	{
		$customer = Customer::create($this->getCustomerData());
		$this
			->loginAs()
			->get(route('customers.index'))
			->assertStatus(200)
			->assertSee($customer->email);
	}

	public function testItShouldBeSeeTheEditCustomersPage()
	{
		$customer = Customer::create($this->getCustomerData());
		$this
			->loginAs()
			->get(route('customers.edit', ['customer' => $customer->id]))
			->assertStatus(200)
			->assertSee($customer->email)
			->assertSee($customer->name);
	}

	public function testItShouldUpdateTheCustomer()
	{
		$customerData = $this->getCustomerData();
		$customer = Customer::create($customerData);
		$customerData["name"] = $this->faker->name;

		$this
			->loginAs()
			->patch(route('customers.update', $customer->id), $customerData)
			->assertStatus(302)
			->assertRedirect(route('customers.index'));
		$this->assertFlashLevel('success');
	}

	public function testItShouldDeleteTheCustomer()
	{
		$customer = Customer::create($this->getCustomerData());

		$this->loginAs()
			->delete(route('customers.destroy', $customer->id))
			->assertStatus(302)
			->assertRedirect(route('customers.index'));
		
		$this->assertFlashLevel('success');

	}

	public function testItShouldNotDeleteIfItHaveRelations()
	{
		$this->assertTrue(true);
		//TODO : This will write after done invoice and revenues tests.
	}

	// Helpers
	private function getCustomerData()
	{
		return [
			'company_id' => $this->company->id,
			'name' => $this->faker->name,
			'email' => $this->faker->email,
			'tax_number' => $this->faker->buildingNumber,
			'phone' => $this->faker->phoneNumber,
			'address' => $this->faker->streetAddress,
			'website' => 'www.akaunting.com',
			'currency_code' => $this->company->currencies()->first()->code,
			'enabled' => $this->faker->boolean ? 1 : 0
		];
	}

	private function getCustomerDataWithUser()
	{
		$password = $this->faker->password;

		return $this->getCustomerData() + [
				'create_user' => 1,
				'locale' => 'en-GB',
				'password' => $password,
				'password_confirmation' => $password
			];
	}
}
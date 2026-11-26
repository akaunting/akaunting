<?php

namespace Tests\Feature\Common;

use App\Jobs\Banking\CreateAccount;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\CreateItem;
use App\Models\Banking\Account;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use Tests\Feature\FeatureTestCase;

class RequestCompanyTest extends FeatureTestCase
{
    public function testItShouldNotMoveARecordToTheCompanySentWithEnableOrDisable()
    {
        $other = $this->createOtherCompany();

        $item = $this->dispatch(new CreateItem(Item::factory()->enabled()->raw()));
        $customer = $this->dispatch(new CreateContact(Contact::factory()->customer()->enabled()->raw()));
        $account = $this->dispatch(new CreateAccount(Account::factory()->enabled()->raw()));

        // The company is in the path, the one sent in the query is another
        $this->loginAs();

        $this->get(route('items.disable', $item->id) . '?company_id=' . $other->id)->assertOk();
        $this->get(route('items.enable', $item->id) . '?company_id=' . $other->id)->assertOk();
        $this->get(route('customers.disable', $customer->id) . '?company_id=' . $other->id);
        $this->get(route('accounts.disable', $account->id) . '?company_id=' . $other->id);

        $this->assertSame($this->company->id, (int) Item::withoutGlobalScopes()->find($item->id)->company_id);
        $customer = Contact::withoutGlobalScopes()->find($customer->id);
        $account = Account::withoutGlobalScopes()->find($account->id);

        // Disabled, in the company they were in
        $this->assertSame($this->company->id, (int) $customer->company_id);
        $this->assertFalse((bool) $customer->enabled);
        $this->assertSame($this->company->id, (int) $account->company_id);
        $this->assertFalse((bool) $account->enabled);
    }

    public function testItShouldNotMoveARecordToTheCompanySentWithABulkAction()
    {
        $other = $this->createOtherCompany();

        $item = $this->dispatch(new CreateItem(Item::factory()->enabled()->raw()));

        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => 'common', 'type' => 'items']), [
                'handle' => 'disable',
                'selected' => [$item->id],
                'company_id' => $other->id,
            ])
            ->assertOk();

        $item = Item::withoutGlobalScopes()->find($item->id);

        $this->assertSame($this->company->id, (int) $item->company_id);
        $this->assertFalse((bool) $item->enabled);
    }

    /**
     * A company the logged in user is not in.
     */
    protected function createOtherCompany(): Company
    {
        $this->loginAs();

        $company = $this->dispatch(new CreateCompany(Company::factory()->enabled()->raw([
            'settings' => ['wizard.completed' => '1', 'email.protocol' => 'array'],
        ])));

        $this->user->companies()->detach($company->id);

        $this->company->makeCurrent();

        return $company;
    }
}

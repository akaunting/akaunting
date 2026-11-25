<?php

namespace Tests\Feature\Common;

use App\Jobs\Auth\CreateUser;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Common\Widget;
use App\Models\Document\Document;
use App\Traits\Permissions;
use Tests\Feature\FeatureTestCase;

class AccessControlTest extends FeatureTestCase
{
    use Permissions;

    public function testItShouldNotCreateAnInlineItemInAnotherCompany()
    {
        $this->loginAs()
            ->post(route('modals.items.store'), [
                'type' => 'inline',
                'field' => 'company_id',
                'value' => '999',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $item = Item::withoutGlobalScopes()->latest('id')->first();

        $this->assertSame($this->company->id, (int) $item->company_id);
        $this->assertFalse(Item::withoutGlobalScopes()->where('company_id', 999)->exists());

        // The field the select sends is still set
        $this->loginAs()
            ->post(route('modals.items.store'), [
                'type' => 'inline',
                'field' => 'name',
                'value' => 'Inline item',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertTrue(Item::where('name', 'Inline item')->exists());
    }

    public function testItShouldNotRunUpdatesWithoutTheUpdatePermission()
    {
        $user = $this->createUserWithPermissions(['read-admin-panel']);

        $this->loginAs($user)->withExceptionHandling();

        $this->get(route('updates.check'))->assertForbidden();
        $this->get(route('updates.run', ['alias' => 'core', 'version' => '1.0.0']))->assertForbidden();
        $this->post(route('updates.steps'), ['alias' => 'core', 'version' => '1.0.0'])->assertForbidden();
        $this->post(route('updates.finish'), ['alias' => 'core', 'version' => '1.0.0'])->assertForbidden();

        $this->loginAs()
            ->post(route('updates.steps'), ['name' => 'Akaunting', 'alias' => 'core', 'version' => '1.0.0'])
            ->assertOk()
            ->assertJson(['success' => true]);
    }

    public function testItShouldNotChangeTheWidgetsOfAnotherUsersDashboard()
    {
        // The widget is on the dashboard of the default user
        $widget = Widget::factory()->create();
        $count = Widget::where('dashboard_id', $widget->dashboard_id)->count();

        $user = $this->createUserWithPermissions([
            'read-admin-panel', 'read-common-dashboards', 'create-common-widgets', 'read-common-widgets', 'update-common-widgets', 'delete-common-widgets',
        ]);

        $this->loginAs($user)->withExceptionHandling();

        $this->get(route('widgets.edit', $widget->id))->assertForbidden();

        $request = [
            'dashboard_id' => $widget->dashboard_id,
            'class' => $widget->class,
            'name' => 'Changed by another user',
        ];

        $this->patch(route('widgets.update', $widget->id), $request)->assertForbidden();
        $this->post(route('widgets.store'), $request)->assertForbidden();
        $this->delete(route('widgets.destroy', $widget->id))->assertForbidden();

        $fresh = Widget::withTrashed()->find($widget->id);

        $this->assertSame($widget->name, $fresh->name);
        $this->assertNull($fresh->deleted_at);
        $this->assertSame($count, Widget::where('dashboard_id', $widget->dashboard_id)->count());
    }

    public function testItShouldStillChangeTheWidgetsOfTheOwnDashboard()
    {
        $widget = Widget::factory()->create();

        $this->loginAs()
            ->patch(route('widgets.update', $widget->id), [
                'dashboard_id' => $widget->dashboard_id,
                'class' => $widget->class,
                'name' => 'Renamed',
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame('Renamed', $widget->fresh()->name);
    }

    public function testItShouldNotBulkExportWithoutTheReadPermission()
    {
        $customer = Contact::factory()->customer()->create();

        $user = $this->createUserWithPermissions(['read-admin-panel']);

        $this->loginAs($user)
            ->post(route('bulk-actions.action', ['group' => 'sales', 'type' => 'customers']), [
                'handle' => 'export',
                'selected' => [$customer->id],
            ])
            ->assertOk()
            ->assertJson(['success' => false, 'error' => true, 'message' => trans('errors.message.403')]);

        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => 'sales', 'type' => 'customers']), [
                'handle' => 'export',
                'selected' => [$customer->id],
            ])
            ->assertOk()
            ->assertDownload();
    }

    public function testItShouldNotResolveADocumentOfAnotherTypeOnTheRoute()
    {
        $invoice = Document::factory()->invoice()->create();
        $bill = Document::factory()->bill()->create();

        $this->loginAs()->withExceptionHandling();

        $this->get(route('invoices.show', $invoice->id))->assertOk();
        $this->get(route('invoices.show', $bill->id))->assertNotFound();
        $this->get(route('invoices.print', $bill->id))->assertNotFound();
        $this->delete(route('invoices.destroy', $bill->id))->assertNotFound();
        $this->get(route('bills.show', $invoice->id))->assertNotFound();

        $this->assertModelExists($bill);
    }

    public function testItShouldNotPreviewDocumentsWithoutTheReadPermission()
    {
        $invoice = Document::factory()->invoice()->create();

        $user = $this->createUserWithPermissions(['read-admin-panel']);

        $this->loginAs($user)
            ->withExceptionHandling()
            ->get(route('preview.invoices.show', $invoice->id))
            ->assertForbidden();
    }

    public function testItShouldNotReachAnotherTypeOverTheApi()
    {
        $invoice = Document::factory()->invoice()->create();
        $bill = Document::factory()->bill()->create();
        $vendor = Contact::factory()->vendor()->create();

        $user = $this->createUserWithPermissions([
            'read-admin-panel', 'read-api', 'read-sales-invoices', 'delete-sales-invoices', 'read-sales-customers',
        ]);

        $api = $this->withExceptionHandling()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($user->email . ':limited-password'),
        ]);

        $api->getJson(route('api.documents.show', [$invoice->id, 'search' => 'type:invoice']))->assertOk();
        $api->getJson(route('api.documents.show', [$bill->id, 'search' => 'type:invoice']))->assertStatus(500)->assertJsonMissing(['type' => 'bill']);
        $api->getJson(route('api.documents.index', ['search' => 'type:invoice or type:bill']))->assertOk()->assertJsonMissing(['type' => 'bill']);
        $api->deleteJson(route('api.documents.destroy', [$bill->id, 'search' => 'type:invoice']))->assertNotFound();
        $api->getJson(route('api.contacts.show', [$vendor->id, 'search' => 'type:customer']))->assertStatus(500)->assertJsonMissing(['email' => $vendor->email]);

        $this->assertModelExists($bill);
    }

    protected function createUserWithPermissions(array $permissions)
    {
        $role = $this->createRole('limited-' . count($permissions));

        foreach ($permissions as $permission) {
            $this->attachPermission($role, $permission);
        }

        return $this->dispatch(new CreateUser(array_merge(user_model_class()::factory()->raw(), [
            // The factory password is already a hash, which the user model hashes again
            'password' => 'limited-password',
            'enabled' => 1,
            'companies' => [$this->company->id],
            'roles' => [$role->id],
        ])));
    }
}

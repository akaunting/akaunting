<?php

namespace Modules\OfflinePayments\Tests\Feature;

use Modules\OfflinePayments\Jobs\CreatePaymentMethod;
use Tests\Feature\FeatureTestCase;

class SettingsTest extends FeatureTestCase
{
    public function testItShouldSeeOfflinePaymentsInSettingsListPage()
    {
        $this->loginAs()
            ->get(route('settings.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('offline-payments::general.description'));
    }

    public function testItShouldSeeOfflinePaymentsSettingsListPage()
    {
        $this->loginAs()
            ->get(route('offline-payments.settings.edit'))
            ->assertStatus(200)
            ->assertSeeText(trans('offline-payments::general.payment_gateways'));
    }

    public function testItShouldSeeOfflinePaymentsSettingsCreatePage()
    {
        $this->loginAs()
            ->get(route('offline-payments.settings.edit'))
            ->assertStatus(200)
            ->assertSeeText(trans('offline-payments::general.add_new'));
    }

    public function testItShouldCreateOfflinePaymentsSettings()
    {
        $request = $this->getRequest();

        $message = trans('messages.success.added', ['type' => $request['name']]);

        $this->loginAs()
            ->post(route('offline-payments.settings.update'), $request)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => $message,
            ]);
    }

    public function testItShouldSeeOfflinePaymentsSettingsUpdatePage()
    {
        $payment_method = $this->dispatch(new CreatePaymentMethod($this->getRequest()));

        $this->loginAs()
            ->post(route('offline-payments.settings.get', ['code' => $payment_method['code']]))
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $payment_method['name'],
                ],
            ]);
    }

    public function testItShouldUpdateOfflinePaymentsSettings()
    {
        $request = $this->getRequest();

        $payment_method = $this->dispatch(new CreatePaymentMethod($request));

        $request['name'] = $this->faker->text(10);
        $request['update_code'] = $payment_method['code'];

        $message = trans('messages.success.updated', ['type' => $request['name']]);

        $this->loginAs()
            ->post(route('offline-payments.settings.update'), $request)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => $message,
            ]);
    }

    public function testItShouldDeleteAccount()
    {
        $payment_method = $this->dispatch(new CreatePaymentMethod($this->getRequest()));

        $message = trans('messages.success.deleted', ['type' => $payment_method['name']]);

        $this->loginAs()
            ->delete(route('offline-payments.settings.delete', ['code' => $payment_method['code']]))
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => $message,
            ]);
    }

    public function getRequest()
    {
        return [
            'name' => $this->faker->text(10),
            'code' => 'offline-payments.cash.99',
            'customer' => 1,
            'order' => 1,
            'description' => $this->faker->paragraph,
        ];
    }
}

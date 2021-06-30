<?php

namespace Tests\Feature\Common;

use App\Jobs\Auth\NotifyUser;
use App\Notifications\Common\ImportCompleted;
use Cache;
use Date;
use Illuminate\Support\Str;
use Tests\Feature\FeatureTestCase;

class NotificationsTest extends FeatureTestCase
{
    public function testItShouldSeeNotificationListPage()
    {
        $this->loginAs()
            ->get(route('notifications.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.items', 2));
    }

    public function testItShouldSeeReadAllAction()
    {
        $this->loginAs()
            ->get(route('notifications.read-all'))
            ->assertStatus(302);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeDisableAction()
    {
        $this->loginAs()
            ->post(route('notifications.disable'), ['path' => 'double-entry', 'id' => 1])
            ->assertOk()
            ->assertSeeText(trans('messages.success.disabled', [
                'type' => Str::lower(trans_choice('general.notifications', 2))
            ]));
    }

    public function testItShouldSeeNewApps()
    {
        $notificatinos = $this->getNewApps();

        $this->loginAs()
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertSeeText('Double-Entry');
    }

    protected function getNewApps()
    {
        $new_apps[] = (object) [
            "name" => "Double-Entry",
            "alias" => "double-entry",
            "message" => "<a href=\"https:\/\/akaunting.com\/apps\/double-entry?utm_source=Notifications&utm_medium=App&utm_campaign=Double-Entry\" target=\"_blank\">Double-Entry<\/a> app is published. You can check it out!",
            "path" =>"new-apps",
            "started_at" => "2021-06-26 00:00:00",
            "ended_at" => "2021-07-11 00:00:00",
            "status" => 2,
        ];

        $key = 'apps.notifications';

        Cache::put($key, ['new-apps' => $new_apps], Date::now()->addHour(6));
    }
}
